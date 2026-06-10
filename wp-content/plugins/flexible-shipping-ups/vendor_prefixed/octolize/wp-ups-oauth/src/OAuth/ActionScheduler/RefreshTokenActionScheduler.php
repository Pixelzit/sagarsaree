<?php

namespace UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\ActionScheduler;

use UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\RestApiToken;
use UpsFreeVendor\Octolize\WooCommerceShipping\Ups\OAuth\TokenOption;
use UpsFreeVendor\Psr\Log\LoggerInterface;
use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\Hookable;
class RefreshTokenActionScheduler implements Hookable
{
    private const TEN_MINUTES = 600;
    private RestApiToken $rest_api_token;
    private LoggerInterface $logger;
    private TokenOption $token_option;
    private ?int $current_action_id = null;
    private string $app;
    public function __construct(string $app, RestApiToken $rest_api_token, TokenOption $token_option, LoggerInterface $logger)
    {
        $this->app = $app;
        $this->rest_api_token = $rest_api_token;
        $this->token_option = $token_option;
        $this->logger = $logger;
        $this->rest_api_token->set_refresh_token_action_scheduler($this);
    }
    public function hooks(): void
    {
        add_action($this->get_refresh_token_action_name(), [$this, 'refresh_token']);
        add_action('flexible_shipping_ups_token_created', [$this, 'schedule']);
        add_action('flexible_shipping_ups_token_refreshed', [$this, 'schedule']);
        add_action('action_scheduler_before_execute', [$this, 'track_current_refresh_action'], 10, 1);
        add_action('action_scheduler_after_execute', [$this, 'clear_current_refresh_action']);
        add_action('action_scheduler_execution_ignored', [$this, 'clear_current_refresh_action']);
        add_action('action_scheduler_failed_execution', [$this, 'clear_current_refresh_action']);
    }
    public function refresh_token(bool $force_refresh = \false): void
    {
        $this->rest_api_token->refresh_token($force_refresh);
    }
    public function get_current_action_id(): ?int
    {
        return $this->current_action_id;
    }
    public function get_refresh_token_action_name(): string
    {
        return 'flexible_shipping_ups_' . $this->app . '_refresh_token';
    }
    public function enqueue(): void
    {
        $this->logger->debug('Enqueuing refresh token action.');
        if (as_has_scheduled_action($this->get_refresh_token_action_name())) {
            $this->logger->debug('Refresh token action already enqueued.');
            return;
        }
        if (!as_enqueue_async_action($this->get_refresh_token_action_name(), [], '', \true)) {
            $this->logger->error('Error during enqueue refresh token action.');
        } else {
            $this->logger->debug('Refresh token action enqueued.');
        }
    }
    public function schedule(): void
    {
        $this->logger->debug('Scheduling refresh token action.');
        $this->cleanup_legacy_async_actions();
        $this->token_option->clear_wp_cache();
        $schedule_timestamp = $this->token_option->get_expires_at() - self::TEN_MINUTES;
        if ($this->has_pending_action_due_no_later_than($schedule_timestamp)) {
            $this->logger->debug('A pending refresh token action already exists past-due or due no later than the target schedule time. Skipping reschedule.');
            return;
        }
        as_unschedule_all_actions($this->get_refresh_token_action_name(), ['force_refresh' => \true]);
        if (!as_schedule_single_action($schedule_timestamp, $this->get_refresh_token_action_name(), ['force_refresh' => \true], '', \false)) {
            $this->logger->error('Error during schedule refresh token action.');
        } else {
            $this->logger->debug('Refresh token action scheduled.');
        }
    }
    public function cleanup_legacy_async_actions(): void
    {
        if (!as_has_scheduled_action($this->get_refresh_token_action_name(), [])) {
            return;
        }
        $this->logger->debug('Removing legacy async refresh token actions.');
        as_unschedule_all_actions($this->get_refresh_token_action_name(), []);
    }
    private function has_pending_action_due_no_later_than(int $schedule_timestamp): bool
    {
        $scheduled_actions = as_get_scheduled_actions(['hook' => $this->get_refresh_token_action_name(), 'args' => ['force_refresh' => \true], 'status' => 'pending', 'date' => max(time(), $schedule_timestamp), 'date_compare' => '<=', 'per_page' => 1, 'orderby' => 'date', 'order' => 'ASC'], 'ids');
        if (null !== $this->current_action_id) {
            $scheduled_actions = array_values(array_filter($scheduled_actions, fn($action_id): bool => (int) $action_id !== $this->current_action_id));
        }
        return [] !== $scheduled_actions;
    }
    /**
     * @internal
     */
    public function track_current_refresh_action(int $action_id): void
    {
        if ($this->get_refresh_token_action_name() !== $this->get_action_hook($action_id)) {
            return;
        }
        $this->current_action_id = $action_id;
    }
    /**
     * @internal
     */
    public function clear_current_refresh_action(): void
    {
        $this->current_action_id = null;
    }
    protected function get_action_hook(int $action_id): string
    {
        return \ActionScheduler::store()->fetch_action($action_id)->get_hook();
    }
}

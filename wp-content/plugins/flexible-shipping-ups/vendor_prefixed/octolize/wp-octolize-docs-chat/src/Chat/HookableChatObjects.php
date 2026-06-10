<?php

namespace UpsFreeVendor\Octolize\Docs\Chat;

use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\HookableCollection;
use UpsFreeVendor\WPDesk\PluginBuilder\Plugin\HookableParent;
use UpsFreeVendor\WPDesk\ShowDecision\ShouldShowStrategy;
class HookableChatObjects implements HookableCollection
{
    use HookableParent;
    private string $plugin_slug;
    private string $plugin_url;
    private string $version;
    private ChatSettings $settings;
    private ShouldShowStrategy $show_strategy;
    public function __construct(string $plugin_slug, string $plugin_url, string $version, ShouldShowStrategy $show_strategy)
    {
        $this->plugin_slug = $plugin_slug;
        $this->plugin_url = $plugin_url;
        $this->version = $version;
        $this->show_strategy = $show_strategy;
    }
    public function hooks()
    {
        $this->add_hookable(new ChatContainer($this->show_strategy, $this->plugin_slug));
        $this->add_hookable(new AjaxChatSettings($this->plugin_slug));
        $this->add_hookable(new Assets($this->plugin_url, $this->version, $this->show_strategy));
        $this->hooks_on_hookable_objects();
    }
}

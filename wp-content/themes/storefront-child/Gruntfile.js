module.exports = function(grunt) {

  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
     sass: {                              // Task
		dist: {                            // Target
		  options: {                       // Target options
			style: 'expanded'
		  },
		  files: {                         // Dictionary of files
			'assets/css/main.css': 'assets/sass/main.scss'       // 'destination': 'source'
			
		  }
		}
	  },
	  watch: {
		sass: {
		  files: ['assets/sass/**/*.scss'],
		  tasks: ['sass'],
		  options: {
			spawn: false,
			interrupt: true,
			atBegin: true,
		  },
		},
	  },
  });
  
  grunt.loadNpmTasks('grunt-contrib-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');

  // Default task(s).
  grunt.registerTask('default', ['watch']);

};
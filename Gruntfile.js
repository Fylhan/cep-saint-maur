module.exports = function(grunt) {

	grunt.config.init({
		pkg: grunt.file.readJSON('package.json'),
		jshint: {
			all: ['Gruntfile.js', 'assets/js/src/*.js']
		},
		concat: {
			css: {
				src: ['assets/css/src/common.css', 'assets/css/src/structure.css', 'assets/css/src/page.css', 'assets/css/src/contact.css'],
				dest: 'assets/css/app.min.css',
			},
			cssadmin: {
				src: ['assets/css/src/*.css', 'assets/css/vendor/*.css'],
				dest: 'assets/css/admin.min.css',
			},
			jscontact: {
				options: {
					separator: ';;',
				},
				src: ['assets/js/vendor/jquery.min.js', 'assets/js/src/contact.js'],
				dest: 'assets/js/contact.min.js',
			},
			jsadmin: {
				options: {
					separator: ';;',
				},
				src: ['assets/js/vendor/jquery.min.js', 'assets/js/vendor/jquery-ui.js', 'assets/js/vendor/jquery.ui.datepicker-fr.js', 'assets/js/vendor/redactor.js', 'assets/js/vendor/redactor-fr.js', 'assets/js/src/admin.js'],
				dest: 'assets/js/admin.min.js',
			}
		},
		uglify: {
			options: {
				banner: '/*! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> */\n'
			},
			jscontact: {
				src: ['assets/js/contact.min.js'],
				dest: 'assets/js/contact.min.js'
			},
			jsadmin: {
				src: ['assets/js/admin.min.js'],
				dest: 'assets/js/admin.min.js'
			}
		}
	});

	grunt.loadNpmTasks('grunt-contrib-jshint');
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-uglify');

	grunt.registerTask('default', ['jshint', 'concat', 'uglify']);

};
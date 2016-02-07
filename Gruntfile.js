/* 
 * This file is part of the makigas corewidgets platform.
 * Copyright (C) 2013-2016 Dani Rodríguez
 */

'use strict';

module.exports = function (grunt) {
  // Load grunt tasks in extra modules.
  require('load-grunt-tasks')(grunt);

  grunt.initConfig({
    // Load project configuration
    pkg: grunt.file.readJSON('package.json'),
    config: {
      src: 'src',
      dist: 'dist'
    },
    clean: {
      dist: ['<%= config.dist %>']
    },
    compress: {
      dist: {
        options: {
          archive: '<%= pkg.name %>.<%= pkg.version %>.zip'
        },
        expand: true,
        cwd: '<%= config.dist %>',
        src: '**/*',
        dest: '<%= pkg.name %>/'
      }
    },
    copy: {
      dist: {
        options: {
          keepSpecialComments: 1
        },
        files: [{
          expand: true,
          dot: true,
          cwd: '<%= config.src %>',
          dest: '<%= config.dist %>',
          src: '**/*.{html,php}'
        }]
      }
    },
    rsync: {
      staging: {
        options: {
          src: './<%= config.dist %>/',
          dest: '/srv/www/makigas/htdocs/wp-content/plugins/videoman/',
          host: 'vagrant@makigas.dev',
          recursive: true,
          delete: true
        }
      }
    },
    uglify: {
        dist: {
            files: {
                '<%= config.dist %>/js/metabox.min.js': ['<%= config.src %>/js/metabox.js']
            }
        }
    }
  });

  grunt.registerTask('build', [
    'clean:dist',
    'uglify:dist',
    'copy:dist'
  ]);
  
  grunt.registerTask('dist', [
    'build',
    'compress'
  ]);
  
  grunt.registerTask('deploy', [
    'build',
    'rsync:staging'
  ]);
};

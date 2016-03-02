/*global module:false*/
module.exports = function (grunt) {

    // Project configuration.
    grunt.initConfig({

        pkg: grunt.file.readJSON('package.json'),

        // Metadata.
        meta: {
            basePath: './',
            sassPath: 'sass/',
            cssPath: 'assets/css/'
        },

        // Task configuration.
        sass: {
            dist: {
                options: {
                    sourcemap: 'none'
                },
                files: {
                    '<%= meta.cssPath %>main.css': '<%= meta.sassPath %>main.scss'
                }
            }
        },

        watch: {
            scripts: {
                files: [
                    '<%= meta.sassPath %>/*.scss'
                ],
                tasks: ['sass']
            }
        }

    });

    // These plugins provide necessary tasks.
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-sass');

    // Default task.
    grunt.registerTask('default', ['sass']);

};
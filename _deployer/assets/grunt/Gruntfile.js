module.exports = function(grunt) {

    grunt.initConfig({
        less: {
            api: {
                options: {
                    sourceMap: true
                },
                files: {
                    '../../../api.css': '../less/api.less'
                }
            },
            index: {
                options: {
                    sourceMap: true
                },
                files: {
                    '../../../index.css': '../less/index.less'
                }
            }
        },
        watch: {
            api: {
                files: ['../less/api/**/*.less'],
                tasks: ['less:api']
            },
            index: {
                files: ['../less/index/**/*.less'],
                tasks: ['less:index']
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-watch');

    grunt.registerTask('default', ['less', 'watch']);

};

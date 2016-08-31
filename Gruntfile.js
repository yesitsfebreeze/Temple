module.exports = function(grunt) {

    grunt.initConfig({
        exec: {
            deploy: {
                cmd: function() {
                    return 'echo $(curl localhost/Temple/deploy.php)';
                }
            }
        },
        watch: {
            files: ['_source/**/*.*'],
            tasks: ['exec:deploy']
        }
    });


    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-exec');

    grunt.registerTask('default', ["exec:deploy", "watch"]);

};
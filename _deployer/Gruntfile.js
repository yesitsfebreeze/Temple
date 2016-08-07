module.exports = function(grunt) {

    grunt.initConfig({
        exec: {
            echo_name: {
                cmd: function() {

                    return 'sh deploy.sh; echo ..deployed';
                }
            }
        },
        watch: {
            files: ['./../_source/**/*.*'],
            tasks: ['exec']
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-exec');

    grunt.registerTask('default', ['watch']);

};
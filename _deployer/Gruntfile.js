module.exports = function(grunt) {

    grunt.initConfig({
        exec: {
            deploy: {
                "cmd": function() {
                    return 'sh deploy.sh; echo ..deployed';
                }
            }
        },
        watch: {
            files: [
                './../_source/**/*.*',
                "./Deployer.php",
                "./pages.yml"
            ],
            tasks: ['exec']
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-exec');

    grunt.registerTask('default', ["exec", "watch"]);

};
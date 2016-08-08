module.exports = function(grunt) {

    grunt.initConfig({
        exec: {
            dev: {
                "cmd": function() {
                    return 'sh deploy_dev.sh; echo ..deployed';
                }
            },
            prod: {
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
            tasks: ['exec:dev']
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-exec');

    grunt.registerTask('default', ["exec:dev", "watch"]);
    grunt.registerTask('dump', ["exec:prod"]);

};
module.exports = function(grunt) {

    grunt.initConfig({
        exec: {
            template: {
                "cmd": function() {
                    return 'sh deploy.sh template; echo ..deployed';
                }
            },
            less: {
                "cmd": function() {
                    return 'sh deploy.sh less; echo ..deployed';
                }
            },
            js: {
                "cmd": function() {
                    return 'sh deploy.sh js; echo ..deployed';
                }
            },
            assets: {
                "cmd": function() {
                    return 'sh deploy.sh assets; echo ..deployed';
                }
            }
        },
        watch: {
            template: {
                files: [
                    './../_source/**/*.tpl',
                    './../_source/**/*.md',
                    "./Deployer.php",
                    "./pages.yml"
                ],
                tasks: ['exec:template']
            },
            less: {
                files: [
                    './../_source/**/*.less'
                ],
                tasks: ['exec:less']
            },
            js: {
                files: [
                    './../_source/**/*.js',
                    './../_source/**/js/**/*.loader.php'
                ],
                tasks: ['exec:js']
            },
            assets: {
                files: [
                    './../_source/**/assets/fonts/*.*',
                    './../_source/**/assets/img/*.*'
                ],
                tasks: ['exec:assets']
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-exec');

    grunt.registerTask('default', ["exec", "watch"]);
    grunt.registerTask('dump', ["exec"]);
    grunt.registerTask('assets', ["exec:assets"]);
    grunt.registerTask('template', ["exec:template"]);

};
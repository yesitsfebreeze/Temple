
module.exports = function(grunt) {

  grunt.initConfig({
    uglify: {
      docs: {
        files: {
          '../docs.js': ['../js/index.js']
        }
      }
    },
    less: {
      docs: {
        files: {
        '../docs.css': ['../js/index.js']  
        }
      } 
    },
    markdown: {
      docs: {
        files: [
          {
            expand: true,
            src: '../../md/*.md',
            dest: '../../html/',
            ext: '.html'
          }
        ]
      }
    },
    watch: {
      
    }
  });

grunt.loadNpmTasks('grunt-markdown');
grunt.loadNpmTasks('grunt-contrib-uglify');
grunt.loadNpmTasks('grunt-contrib-less');
grunt.loadNpmTasks('grunt-contrib-watch');
grunt.registerTask('default', ["uglify","less","markdown","watch"]);
};
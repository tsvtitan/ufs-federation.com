/**
 * Run predefined tasks whenever watched file patterns are added, changed or deleted.
 *
 * ---------------------------------------------------------------
 *
 * Watch for changes on
 * - files in the `assets` folder
 * - the `tasks/pipeline.js` file
 * and re-run the appropriate tasks.
 *
 * For usage docs see:
 * 		https://github.com/gruntjs/grunt-contrib-watch
 *
 */
module.exports = function(grunt) {

	grunt.config.set('watch', {
    
    dev: {

			// Assets to watch:
			files: ['api/**/*','assets/**/*', 'tasks/pipeline.js'],

			// When assets are changed:
			tasks: ['syncAssets' , 'linkAssets']
		},
    prod: {
      
      files: ['api/**/*','assets/**/*', 'tasks/pipeline.js'],

			// When assets are changed:
			tasks: ['syncAssets', 
              'concat',
              'uglify',
              'cssmin',
              'linkAssetsProd']
    }
	});

	grunt.loadNpmTasks('grunt-contrib-watch');
};

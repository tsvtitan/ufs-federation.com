Number.prototype.mod = function(n) {
return ((this%n)+n)%n;
}

$(document).ready(function(){
    var iPad = navigator.userAgent.match(/iPad/i) != null;
    /*
    function setFocus() {
        if(iPad){
            var id = active_cell['$input'].get();
            alert(id);
            var eventObject = document.createEvent('TextEvent'); //create a TextEvent object
            eventObject.initTextEvent('textInput', true, true, null, " "); //Initalize it, and pass in a space
            alert(id.dispatchEvent);
            id.dispatchEvent(eventObject); //fire the event
            id.value = id.value.substring(0, text1.value.length - 1); //remove the space
        }
    }*/


	function getIdFromRowAndCol(r, c){
		return '#cell-' + r + "-" + c;
	}					

    function getRowAndColFromId(id){
        var parts = id.split("-");
        return {"row" : parts[1], "col" : parts[2]};
    }

    function grade(){
        var errors = 0;
        var correct = 0;
        
        var cells = $('.crossword td.highlighted');
        var inputs = $('.crossword td.highlighted .hidden-field');
        cells.removeClass('wrong, correct');
        for(var i = 0; i < cells.length; i++){
            var $cell = $(cells[i]);
            var r_c = getRowAndColFromId($cell.attr("id"));
            var r = r_c['row'], c = r_c['col']; 
            var user_char = $(inputs[i]).val().toUpperCase();
            var expected_char = grid[r][c]['char'].toUpperCase();
            if(user_char != expected_char){
                cells.addClass("wrong");
                cells.removeClass("highlighted");
                return;
            }
        }
        
          cells.removeClass('highlighted').removeClass('wrong');
          cells.addClass('correct');
          errors = $('.crossword td.wrong').length;
          correct = $('.crossword td.correct').length;
          if (correct == 87 && errors == 0) {
            $('.crossword td.correct input').prop('disabled', true);
            $('#checkword-btn').slideUp(100);
            $('#form-contacts').slideDown(300,function(){
              $('body').scrollTo($('#thanks').offset().top-20, {duration: 300});
              $('#namexx').focus();
              $('#data-control').val('c'+correct+'r'+errors);
              
            }); 
          }
    }

    $('#grade').click(grade);
	
	function highlightWordAcross(r, c){
		$('.crossword td.highlighted').removeClass("highlighted");
		// highlight to the left
		var i = 0;
		while(c-i >= 0 && grid[r][c-i] != null){
			var id = getIdFromRowAndCol(r, c-i);
			$(id).addClass("highlighted");
			i++;
		}
		
		// to the right
		var i = 0;
		while(c+i < grid[r].length && grid[r][c+i] != null){
			var id = getIdFromRowAndCol(r, c+i);
			$(id).addClass("highlighted");
			i++;
		}
	}
	
	function highlightWordDown(r, c){
		$('.crossword td.highlighted').removeClass("highlighted");
		// highlight up
		var i = 0;
		while(r-i >= 0 && grid[r-i][c] != null){
			var id = getIdFromRowAndCol(r-i, c);
			$(id).addClass("highlighted");
			i++;
		}
		
		// to the right
		var i = 0;
		while(r+i < grid.length && grid[r+i][c] != null){
			var id = getIdFromRowAndCol(r+i, c);
			$(id).addClass("highlighted");
			i++;
		}
	}	
	
	var active_cell = {"ready" : false}
	function setActiveCell(r, c, index, is_across){
		if(active_cell['ready'])
			active_cell['$'].removeClass("active");
			
		var id = getIdFromRowAndCol(r, c);			
		active_cell['$input'] = $(id + ' input');

		$(id).addClass("active");
		
		active_cell['ready'] = true;		
		active_cell['$'] = $(id);
		active_cell['row'] = r;
		active_cell['col'] = c;
		if(index != null){
			active_cell['index'] = index;
			active_cell['starting_row'] = index_to_row_column[index]['row'];
			active_cell['starting_col'] = index_to_row_column[index]['col'];	
		}
		active_cell['is_across'] = is_across;
	}
	
	function isCellActive(r, c){
		return $(getIdFromRowAndCol(r, c)).hasClass('active');
	}
	
	function isCellHighlighted(r, c){
		return $(getIdFromRowAndCol(r, c)).hasClass('highlighted');		
	}
	
	// make sure the offset is calculated correctly
	$('#across-box ol').scrollTop(0);
	$('#down-box ol').scrollTop(0);	
	var move_to_offset = $('#down li:first, #across li:first').position().top;
	function highlightClue(index, is_across){
		$('#clues li').removeClass('active');		
		if(is_across){
			$('#across-box ol').scrollTop(0)
			var to = $("#clue-" + index).addClass('active').position();			
			$('#across-box ol').scrollTop(to.top-move_to_offset)
		} else {
			$('#down-box ol').scrollTop(0)			
			var to = $("#clue-" + index).addClass('active').position();			
			$('#down-box ol').scrollTop(to.top-move_to_offset)
		}
	}

	var highlight_across = true;
	function onCellClick(r, c, forced_direction){
		var cell = grid[r][c];
		forced_direction = forced_direction || false;
		// ambigous cell
		if(cell['across'] && cell['down'] && !forced_direction){
			if(isCellActive(r, c)){
				highlight_across = !highlight_across;	
			} else if(!isCellHighlighted(r, c)){
				highlight_across = true;
			}
			
			if(highlight_across){
				highlightWordAcross(r, c);	
				var index = grid[r][c]['across']['index'];
			} else {
				highlightWordDown(r, c);		
				var index = grid[r][c]['down']['index'];			
			}						
		} else if((cell['across'] != null && !forced_direction) || forced_direction == "across"){
			highlightWordAcross(r, c);	
			highlight_across = true;
			var index = grid[r][c]['across']['index'];
		} else {
			highlightWordDown(r, c);		
			highlight_across = false;
			var index = grid[r][c]['down']['index'];			
		}
		setActiveCell(r, c, index, highlight_across);
		highlightClue(index, highlight_across);
		current_index = index;
        if(!iPad){
            active_cell['$input'].focus();
            active_cell['$input'].select();
        }
	}
	
	function getOnCellClickFunction(r, c){		
		return function(e, forced_direction){
			onCellClick(r, c, forced_direction);
		}
	}	
	
	function highlightNextWord(){
		var r = active_cell['starting_row'];
		var c = active_cell['starting_col'];
		// check to see if we should go down next
		if(active_cell['is_across'] && grid[r][c]['down'] && grid[r][c]['down']['is_start_of_word']){	
			$(getIdFromRowAndCol(r, c)).trigger('click', ['down']);	
			return;
		}
		
		c = (c + 1) % grid[r].length;
		if(c == 0) r = (r + 1) % grid.length;
		for(; true; r = (r + 1) % grid.length, c = 0){
			for(; c < grid[r].length; c++){
				if(!grid[r][c]) continue;
				
				if(grid[r][c]['across'] && grid[r][c]['across']['is_start_of_word']){
					$(getIdFromRowAndCol(r, c)).trigger("click", ['across']);						
					return;
				}
				
				if(grid[r][c]['down'] && grid[r][c]['down']['is_start_of_word']){
					$(getIdFromRowAndCol(r, c)).trigger("click", ['down']);						
					return;
				}				
			}
		}
	}
	
	function highlightPreviousWord(){
		var r = active_cell['starting_row'];
		var c = active_cell['starting_col'];
		// check to see if we should go down next
		if(!active_cell['is_across'] && grid[r][c]['across'] && grid[r][c]['across']['is_start_of_word']){	
			$(getIdFromRowAndCol(r, c)).trigger('click', ['across']);	
			return;
		}

		c = (c - 1).mod(grid[r].length);
		if(c == (grid[r].length - 1)) r = (r - 1).mod(grid.length);
		for(; true; r = (r - 1).mod(grid.length), c = grid[r].length - 1){
			for(; c >= 0; c--){
				if(!grid[r][c]) continue;
				
				if(grid[r][c]['down'] && grid[r][c]['down']['is_start_of_word']){
					$(getIdFromRowAndCol(r, c)).trigger("click", ['down']);						
					return;
				}
				
				if(grid[r][c]['across'] && grid[r][c]['across']['is_start_of_word']){
					$(getIdFromRowAndCol(r, c)).trigger("click", ['across']);						
					return;
				}				
			}
		}
	}	
		
	function onDownClueClick(e){
		var id = $(this).attr('id');
		var index = id.replace(/clue-/, '');
		var r = index_to_row_column[index]['row'];
		var c = index_to_row_column[index]['col'];
		var id = getIdFromRowAndCol(r, c);
		window.location.hash = id.replace(/#/, '');
	    //window.location.hash = ""	
		$(id).trigger("click", ['down']);
	}
	
	function onAcrossClueClick(e){
		var id = $(this).attr('id');
		var index = id.replace(/clue-/, '');
		var r = index_to_row_column[index]['row'];
		var c = index_to_row_column[index]['col'];
		var id = getIdFromRowAndCol(r, c);
		window.location.hash = id.replace(/#/, '');
	    //window.location.hash = ""	
		$(id).trigger("click", ['across']);
	}	
	
	
	var down = $('#down li');
	for(var i = 0; i < down.length; i++){
		$(down[i]).click(onDownClueClick);
	}
	
	var across = $('#across li');
	for(var i = 0; i < across.length; i++){
		$(across[i]).click(onAcrossClueClick);
	}	
	
	// attach event to every non empty cell
	for(var r = 0; r < grid.length; r++){
		for(var c = 0; c < grid[r].length; c++){
			if(grid[r][c] == null) continue;
			var id = getIdFromRowAndCol(r, c);
			$(id).click(getOnCellClickFunction(r, c))
		}
	}	

	$('.hidden-field').keyup(function(e){
		if(!active_cell['ready']) return true;
        if(!iPad){
            active_cell['$input'].focus();
            active_cell['$input'].select();
        }
        return false;
    });

	$('.hidden-field').keypress(function(e){
		if(!active_cell['ready']) return true;

        if(!iPad){
            active_cell['$input'].focus();
            active_cell['$input'].select();
        }

        if(isCharacterKeyPress(e)){
            if(iPad){
                var keyCode = e.keyCode;
                if(e.keyCode <= 105 && e.keyCode >= 96){
                    keyCode -= 48;
                }
                active_cell['$input'].val(String.fromCharCode(keyCode));
            }
			var r = active_cell['row'];
			var c = active_cell['col'];
			if(highlight_across){
				if($(getIdFromRowAndCol(r, c+1)).hasClass('highlighted'))
					setActiveCell(r, c + 1, null, highlight_across);
			} else {
				if($(getIdFromRowAndCol(r+1, c)).hasClass('highlighted'))		
					setActiveCell(r+1, c, null, highlight_across);		
                    // move down
			}
        }

    })
	
	$('.hidden-field').keydown(function(e){
		// no need to do anything until the user has clicked a cell
		if(!active_cell['ready']) return true;

        if(!iPad){
            active_cell['$input'].focus();
            active_cell['$input'].select();
        }
		
		if(e.keyCode == 8) { // delete key
            e.preventDefault();
			if($.trim(active_cell['$input'].val()) != ""){
				active_cell['$input'].val("");
			} else {
				var r = active_cell['row'];
				var c = active_cell['col'];
				if(highlight_across){
					if($(getIdFromRowAndCol(r, c-1)).hasClass('highlighted'))
						setActiveCell(r, c - 1, null, highlight_across);
				} else {
					if($(getIdFromRowAndCol(r-1, c)).hasClass('highlighted'))
						setActiveCell(r-1, c, null, highlight_across);
				}	
				active_cell['$input'].val("");
			}
		} else if(e.shiftKey && e.keyCode == 9){
            e.preventDefault();
			      highlightPreviousWord();
		} else if(e.keyCode == 13 || e.keyCode == 9){
            e.preventDefault();
            grade();
			      highlightNextWord();
        }
		return true;
	});
});

function isCharacterKeyPress(evt) {
    if (typeof evt.which == "undefined") {
        // This is IE, which only fires keypress events for printable keys
        return true;
    } else if (typeof evt.which == "number" && evt.which > 0) {
        // In other browsers except old versions of WebKit, evt.which is
        // only greater than zero if the keypress is a printable key.
        // We need to filter out backspace and ctrl/alt/meta key combinations
        return !evt.ctrlKey && !evt.metaKey && !evt.altKey && evt.which != 8;
    }
    return false;
}
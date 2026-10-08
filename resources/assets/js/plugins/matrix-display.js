(function() {

    this.MatrixDisplay = function() {

        this.gchars = {

        	'A':[ [2,3,4,5],[1,4],[1,4],[2,3,4,5],[] ],
        	'B':[ [1,2,3,4,5],[1,3,5],[1,3,5],[1,2,4,5],[] ],
        	'C':[ [2,3,4],[1,5],[1,5],[] ],
        	'D':[ [1,2,3,4,5],[1,5],[1,5],[2,3,4],[] ],
        	'E':[ [1,2,3,4,5],[1,3,5],[1,3,5],[] ],
        	'F':[ [1,2,3,4,5],[1,3],[1,3],[] ],
        	'G':[ [1,2,3,4,5],[1,5],[1,3,5],[1,3,4,5],[] ],
        	'H':[ [1,2,3,4,5],[3],[3],[1,2,3,4,5],[] ],
        	'I':[ [1,2,3,4,5],[] ],
        	'J':[ [4,5],[5],[1,2,3,4,5],[] ],
        	'K':[ [1,2,3,4,5],[3],[2,4],[1,5],[] ],
        	'L':[ [1,2,3,4,5],[5],[5],[] ],
        	'M':[ [1,2,3,4,5],[1],[2],[1],[1,2,3,4,5],[] ],
        	'N':[ [1,2,3,4,5],[2],[3],[4],[1,2,3,4,5],[] ],
        	'O':[ [2,3,4],[1,5],[1,5],[2,3,4],[] ],
        	'P':[ [1,2,3,4,5],[1,3],[1,2,3],[] ],
        	'Q':[ [2,3,4],[1,5],[1,4,5],[2,3,4,5],[] ],
        	'R':[ [1,2,3,4,5],[1,4],[1,2,3,5],[] ],
        	'S':[ [1,2,3,5],[1,3,5],[1,3,4,5],[] ],
        	'T':[ [1],[1,2,3,4,5],[1],[] ],
        	'U':[ [1,2,3,4,5],[5],[5],[1,2,3,4,5],[] ],
            'V':[ [1,2,3,4],[5],[5],[1,2,3,4],[] ],
        	'W':[ [1,2,3,4,5],[5],[4],[5],[1,2,3,4,5],[] ],
        	'X':[ [1,5],[2,4],[3],[2,4],[1,5],[] ],
        	'Y':[ [1],[2],[3,4,5],[2],[1],[] ],
        	'Z':[ [1,4,5],[1,3,5],[1,2,5],[] ],
            '1':[ [1],[1,2,3,4,5],[] ],
            '2':[ [1,4,5],[1,3,5],[1,2,5],[] ],
            '3':[ [1,3,5],[1,3,5],[1,2,4,5],[] ],     
            '4':[ [1,2,3],[3],[2,3,4,5],[] ],
            '5':[ [1,2,3,5],[1,3,5],[1,3,5],[1,4,5],[] ],
            '6':[ [1,2,3,4,5],[1,3,5],[1,3,5],[1,3,4,5],[] ],
            '7':[ [1,4,5],[1,3],[1,2],[] ],
            '8':[ [1,2,3,4,5],[1,3,5],[1,3,5],[1,2,3,4,5],[] ],   
            '9':[ [1,2,3,5],[1,3,5],[1,3,5],[1,2,3,4,5],[] ], 
            '0':[ [1,2,3,4,5],[1,5],[1,5],[1,2,3,4,5],[] ],
            '?':[ [1],[1,3,5],[1,2],[] ],
            '!':[ [1,2,3,5],[] ],
            '_':[ [5],[5],[5],[] ],
            '-':[ [3],[3],[3],[] ],
            '|':[ [1,2,3,4,5],[] ],
            '+':[ [3],[2,3,4],[3],[] ],
            '=':[ [2,4],[2,4],[2,4],[] ],
        	' ':[ []] 
        };

        this.tTimer = null;  //transform timer
        this.dTimer = null; //duration timer
        this.xoffset = 0;
        this.yoffset = 0;

        this.matrix = [];  //display matrix

        this.randomColors = ['#d6e685', '#8cc665', '#44a340', '#1e6823']; //defaults colors
        this.backgroundColor = '#eeeeee'; //default background color
        this.textColor = '#1e6823'; //default text color

        // Define option defaults
        var defaults = {
            debug: false,
            containerEl: '.js-calendar-graph-svg',  //container element
            groupEl: 'g',   //group column element
            pointEl: 'rect',    //point element 'dot/point'
            fillAttribute: 'fill', //fill for svg, background or background-color for normal elements
            cols: 60,   //columns
            rows: 7,    //rows
            repeat: true, //repeat slides
            slide: -1,  //composition/slide number
            compositions: [] //array of composition objects
        }

        // create options by extending defaults with the passed in arugments
        if (arguments[0] && typeof arguments[0] === "object") {
            this.options = extendDefaults(defaults, arguments[0]);
        }

        // create the events
        this.endSequence = new CustomEvent('endSequence', { 'detail': 'End of slides sequence' });
        this.endSlide = new CustomEvent('endSlide', { 'detail': 'End of slide animation' });
        this.startSlide = new CustomEvent('startSlide', { 'detail': 'End of slide animation' });       
        
        initializeEvents.call(this);

    }

    MatrixDisplay.prototype.run = function(compositions) {
        start.call(this, compositions);
    }

    MatrixDisplay.prototype.stop = function() {

        disableAnimation.call(this);

    }


    MatrixDisplay.prototype.animateText = function(composition) {

        if (!('speed' in composition)) composition.speed = 100;
        if (!('invert' in composition)) composition.invert = false;
        if (!('fx' in composition)) composition.fx = 'none';

        animateText.call(this, composition);
    }    

    MatrixDisplay.prototype.displayText = function(composition) {

        if (!('invert' in composition)) composition.invert = false;

        this.xoffset = this.options.cols;
        this.yoffset = 0;
        _setText.call(this, composition.text);
        displayMatrix.call(this, composition);
    }    

    MatrixDisplay.prototype.option = function(option, value) {
        if (typeof(value) != 'undefined')
            return setOption.call(this, option, value);
        else
            return getOption.call(this, option);
    }

    function extendDefaults(source, properties) {
        var property;
        for (property in properties) {
            if (properties.hasOwnProperty(property)) {
                source[property] = properties[property];
            }
        }
        return source;
    }

    function setOption(option, value) {
        return this.options[option] = value;
    }

    function getOption(option) {
        return this.options[option];
    }

    function initializeEvents() {
        var _this = this;

        document.addEventListener("endSlide", function(e) {
          if (_this.options.debug) console.log(e.detail);
        });

        document.addEventListener("endSequence", function(e) {
          if (_this.options.debug) console.log(e.detail);
        });

        document.addEventListener("startSlide", function(e) {
          if (_this.options.debug) console.log(e.detail);
        });        

    }

    function hexToRgb(hex) {
        var bigint = parseInt(hex, 16);
        var r = (bigint >> 16) & 255;
        var g = (bigint >> 8) & 255;
        var b = bigint & 255;

        return r + "," + g + "," + b;
    }    

    function getTextWidth(text) {

        text = text.toUpperCase();
        var c = 0;

        for (i = 0; i < text.length; i++) {
            var r = 0;
            var l = this.gchars[text[i]];
            c += l.length + 1;
        }

        return c;
    }

    function _genTextMatrix(text) {

        var textMatrix = [];
        for (i = 0; i < text.length; i++) {
            var l = (typeof this.gchars[text[i]] != "undefined") ? this.gchars[text[i]] : '[]';
            for (j = 0; j < l.length; j++) {
                textMatrix.push(l[j]);
            }
        }
        return textMatrix;
    }

    function _genDisplayMatrix(text) {

        this.matrix = [];
        var textMatrix = _genTextMatrix.call(this, text);

        for (var i = 0; i < this.options.cols * 2; i++) {
            this.matrix.push([]);

            /*for (var j = 0; j < this.options.rows; j++) {
                this.matrix[i].push([]);
            }*/
            if (i == this.options.cols) this.matrix = this.matrix.concat(textMatrix);
        }

    }

    function _setText(text) {
        var t = text.toUpperCase();
        _genDisplayMatrix.call(this, t);
    }

    function displayMatrix(composition) {

        if (!('invert' in composition)) composition.invert = false;
        if (!('colors' in composition)) composition.colors = this.randomColors;
        // some trick - default background (git palette), slice darker colors for better visibility
        if (!('background' in composition) && composition.invert) { composition.background = this.textColor; composition.colors = composition.colors.slice(0, 2); }
        if (!('background' in composition)) composition.background = this.backgroundColor;        

        var _this = this;
        
        //background
        if (composition.invert) {

            jQuery(this.options.containerEl).find(this.options.pointEl).each(function() {
                var rcolor = composition.colors[Math.floor((Math.random() * composition.colors.length))];
                jQuery(this).attr(_this.options.fillAttribute, rcolor);
            });

        } else jQuery(this.options.containerEl).find(this.options.pointEl).attr(this.options.fillAttribute, composition.background);


        var co = 1; //column offset from left

        for (var c = 0; c < this.options.cols; c++) {

            if (typeof this.matrix[c + this.xoffset] != 'undefined') {
                for (rect = 0; rect < this.matrix[c + this.xoffset].length; rect++) {

                    var gcolor = (composition.invert == false) ? composition.colors[Math.floor((Math.random() * composition.colors.length))] : composition.background;
                    
                    var ypoint = this.matrix[c + this.xoffset][rect]+this.yoffset;
                    if (ypoint >= 0) jQuery(this.options.containerEl).find(this.options.groupEl).eq(c + co).find(this.options.pointEl).eq(ypoint).attr(this.options.fillAttribute, gcolor);
                }
            }

        }
    }

    function disableAnimation() {
        if (this.tTimer != null) clearInterval(this.tTimer);
        if (this.dTimer != null) clearTimeout(this.dTimer);

        this.tTimer = null;
        this.dTimer = null;
    }

    function animateText(composition) {

        if (!('speed' in composition)) composition.speed = 100;
        if (!('invert' in composition)) composition.invert = false;
        if (!('fx' in composition)) composition.fx = 'none';

        if (this.tTimer != null) clearInterval(this.tTimer);

        if (composition.fx == 'left') { this.yoffset = 0; this.xoffset = 0; }
        if (composition.fx == 'right') { this.yoffset = 0; this.xoffset = (this.options.cols * 2) - 1; }

        if (composition.fx == 'up') { this.xoffset = this.options.cols; this.yoffset = this.options.rows-1 };
        if (composition.fx == 'down') { this.xoffset = this.options.cols; this.yoffset = (this.options.rows*(-1))+1 };

        if (composition.fx == 'none') { this.yoffset = 0; this.xoffset = this.options.cols; }

        _setText.call(this, composition.text);

        var _this = this;
        this.tTimer = setInterval(function() {

            if (_this.xoffset < 0 || _this.xoffset > _this.matrix.length || Math.abs(_this.yoffset) > _this.options.rows ) {

                disableAnimation.call(_this);
                //fire event
                document.dispatchEvent(_this.endSlide); 
                //next slide
                nextComposition.call(_this);
            } else
            {
	            if (composition.fx == 'left') _this.xoffset++;
	            if (composition.fx == 'right') _this.xoffset--;
	            if (composition.fx == 'up') _this.yoffset--;
	            if (composition.fx == 'down') _this.yoffset++;

	            displayMatrix.call(_this, composition);
            }

        }, composition.speed);
    }

    function nextComposition() {
        
        //end of sequence
        if ((this.options.slide + 1 > this.options.compositions.length-1)) {
            //fire end of sequence event
            document.dispatchEvent(this.endSequence); 
            //check repeat option        
            if (this.options.repeat == false) return;
        }

        //next composition
        var nextNo = ((this.options.slide + 1) > this.options.compositions.length - 1) ? 0 : this.options.slide + 1;
        this.options.slide = nextNo;

        document.dispatchEvent(this.startSlide);
        
        if (this.options.debug) console.log('next composition: ' + nextNo);

        var composition = this.options.compositions[nextNo];
        
        //default required composition values
        if (!('speed' in composition)) composition['speed'] = 50;
        if (!('duration' in composition)) composition['duration'] = 2000;
        
        if (this.options.debug) console.log(composition);

        if (typeof composition['fx'] == 'undefined') composition['fx'] = 'none';

        switch (composition['fx']) {
            case 'left':
            case 'right':
            case 'up':
            case 'down':
                animateText.call(this, composition);
                break;
            case 'none':
		        this.xoffset = this.options.cols;
		        this.yoffset = 0;

                animateText.call(this, composition);

                var _this = this;
                //non effect duration
                this.dTimer = setTimeout(function() {

                    disableAnimation.call(_this);

                    //fire event
                    document.dispatchEvent(_this.endSlide); 
                    //next slide
                    nextComposition.call(_this);

                }, composition['duration']);

                /*_setText.call(this, composition['text']);            
                displayText.call(this, composition['color'], composition['invert']);*/
                break;
        }
    }

    function start(compositions) {
        this.options.slide = -1;
        if (typeof compositions != 'undefined') this.options.compositions = compositions; 

        if (this.options.compositions.length > 0) {
            nextComposition.call(this);
        }
    }

}());
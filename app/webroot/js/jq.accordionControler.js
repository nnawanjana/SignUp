(function($) {

    $.extend($.fn, {

        accordionControler: function( options ) {

            // if nothing is selected, return nothing; can't chain anyway
            if (!this.length) {
                return;
            }


            var accordionControler = $.data(this[0], 'accordionControler');
            if ( accordionControler ) {
                return accordionControler;
            }

            // Add novalidate tag if HTML5.
            this.attr('novalidate', 'novalidate');

            accordionControler = new $.accordionControler( options, this[0] );
            $.data(this[0], 'accordionControler', accordionControler);


            return accordionControler;
        }
    });


    // constructor for accordionControler
    $.accordionControler = function( options ) {
        this.settings = $.extend( true, {}, $.accordionControler.defaults, options );
        this.init();
    };


    $.extend($.accordionControler, {

        defaults: {
			accordionControler:{
				"PlanExplanation": {
					"@index": 0,
					"@class":"signup_plan",
					"@complete":true
				},
				"AccountDetails": {
					"@index": 1,
					"@class":"signup_contact",
					"@complete":false
				},
				"PersonalDetails": {
					"@index": 2,
					"@class":"signup_identification",
					"@complete":false
				},
				"PropertyDetails": {
					"@index": 3,
					"@class":"signup_supply",
					"@complete":false
				},
				"PlanOptions": {
					"@index": 4,
					"@class":"signup_options",
					"@complete":false,
				},
				"TACDetails": {
					"@index": 5,
					"@class":"signup_tac",
					"@complete":false,
				}
			}
        },

        setDefaults: function(settings) {
            $.extend( $.accordionControler.defaults, settings );
        },

        veifyFormCompletion: function(name) {
			for (var i in this.defaults.accordionControler){
				if ((i == name) && (this.defaults.accordionControler[i]["@complete"] == true)){
					return true;
				}
			}
			return false;
        },
		updateFormCompletion: function(name, _value){
			this.defaults.accordionControler[name]["@complete"] = _value;
			return this.defaults.accordionControler[name]["@complete"];
		},
		getNextPanel:function(_currentPanelName){
			var currentIndex = this.defaults.accordionControler[_currentPanelName]["@index"];
			var nextIndex = -1;
			for (var i in this.defaults.accordionControler){
				if ((this.defaults.accordionControler[i]["@index"] > currentIndex) && (!this.defaults.accordionControler[i]["@complete"])){
					nextIndex = this.defaults.accordionControler[i]["@index"];
					break;
				}
			}
			if (nextIndex == -1){nextIndex = (this.defaults.accordionControler[i]["@index"] + 1);}
			return nextIndex;
		},
		getIndex:function(_name){

			return this.defaults.accordionControler[_name]["@index"];
		}
    });
})(jQuery);

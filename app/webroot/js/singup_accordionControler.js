(function($) {

    $.extend($.fn, {

        accordionControler: function( options ) {

            // if nothing is selected, return nothing; can't chain anyway
            if (!this.length) {
                options && options.debug && window.console && console.warn( "nothing selected, can't validate, returning nothing" );
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
            messages: {},
            groups: {},
            rules: {},
            errorClass: "error",
            validClass: "valid",
            errorElement: "label",
            focusInvalid: true,
            errorContainer: $( [] ),
            errorLabelContainer: $( [] ),
            onsubmit: true,
            ignore: ":hidden",
            ignoreTitle: false,
            unhighlight: function(element, errorClass, validClass) {
                if (element.type === 'radio') {
                    
                } else {
                    
                }
            },
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
				"TACDetails": {
					"@index": 4,
					"@class":"signup_tac",
					"@complete":false,
				},
				"OptionalDetails": {
					"@index": 5,
					"@class":"signup_options",
					"@complete":false,
				}				
			}			
        },

        setDefaults: function(settings) {
            $.extend( $.accordionControler.defaults, settings );
        },

        messages: {
            required: "This field is required.",
            remote: "Please fix this field.",
            email: "Please enter a valid email address.",
            url: "Please enter a valid URL.",
            date: "Please enter a valid date.",
            dateISO: "Please enter a valid date (ISO).",
            number: "Please enter a valid number.",
            digits: "Please enter only digits.",
            creditcard: "Please enter a valid credit card number.",
            equalTo: "Please enter the same value again.",
            notEqual: "Please specify a different (non-default) value",
            accept: "Please enter a value with a valid extension."
        },

        autoCreateRanges: false,

        prototype: {

            init: function() {

                this.submitted = {};
                this.valueCache = {};
                this.pendingRequest = 0;
                this.pending = {};
                this.invalid = {};
                this.reset();

            }
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
			console.log("currentIndex = " + currentIndex);
			var nextIndex = -1;
			for (var i in this.defaults.accordionControler){
				if ((this.defaults.accordionControler[i]["@index"] > currentIndex) && (!this.defaults.accordionControler[i]["@complete"])){
					nextIndex = this.defaults.accordionControler[i]["@index"];
					break;
				}
			}
			if (nextIndex == -1){nextIndex = (this.defaults.accordionControler[i]["@index"] + 1);}
			
			console.log("Next index = " + nextIndex)
			return nextIndex;
		},
		getIndex:function(_name){
		//console.log(_name);
			return this.defaults.accordionControler[_name]["@index"];
		},

        methods: {
            required: function(value, element, param) {

            },
            remote: function(value, element, param) {

            }
        }

    });


})(jQuery);

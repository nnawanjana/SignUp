

$(function() {
	var concessions = '<concessions><statement id="1" name="statement_01">			<statement_txt>Are you happy for [retailer] to confirm your concession details and eligibility with Centrelink, Veteran Affairs or te Department of Communities?</statement_txt>			<instantiation>				<invocatin>					<type>YesNo</type>				</invocatin>			</instantiation>			<responses>				<response>					<user_statement>Yes</user_statement>					<subsequent_question>8</subsequent_question>				</response>				<response>					<user_statement>No</user_statement>					<subsequent_question>4</subsequent_question>				</response>			</responses>		</statement><statement id="4" name="statement_04">			<statement_txt>Can you please confirm this is your prinicipal place of residence and the only residence in Queensland for which you claim the rebate?</statement_txt>			<instantiation>				<invocatin>					<type>YesNo</type>				</invocatin>			</instantiation>			<responses>				<response>					<user_statement>Yes</user_statement>					<subsequent_question>7</subsequent_question>				</response>				<response>					<user_statement>No</user_statement>					<subsequent_question>2</subsequent_question>				</response>			</responses>		</statement><statement id="5" name="statement_05">			<statement_txt>Apart from a spouse or dependant, does anybody else live in the property?</statement_txt>			<instantiation>				<invocatin>					<type>YesNo</type>				</invocatin>			</instantiation>			<responses>				<response>					<user_statement>Yes</user_statement>					<subsequent_question>6</subsequent_question>				</response>				<response>					<user_statement>No</user_statement>					<subsequent_question>0</subsequent_question>				</response>			</responses>		</statement><statement id="6" name="statement_06">			<statement_txt>Does that person hold a concession card?</statement_txt>			<instantiation>				<invocatin>					<type>YesNo</type>				</invocatin>			</instantiation>			<responses>				<response>					<user_statement>Yes</user_statement>					<subsequent_question>7</subsequent_question>				</response>				<response>					<user_statement>No</user_statement>					<subsequent_question>0</subsequent_question>				</response>			</responses>		</statement><statement id="7" name="statement_07">			<statement_txt>Does that person pay rent?</statement_txt>			<instantiation>				<invocatin>					<type>YesNo</type>				</invocatin>			</instantiation>			<responses>				<response>					<user_statement>Yes</user_statement>					<subsequent_question>11</subsequent_question>				</response>				<response>					<user_statement>No</user_statement>					<subsequent_question>8</subsequent_question>				</response>			</responses>		</statement><statement id="8" name="statement_08">			<statement_txt>Does that person recieve income assistance from Centrelink, Family Assistance Office or Department of Veteran Affairs?</statement_txt>			<instantiation>				<invocatin>					<type>YesNo</type>				</invocatin>			</instantiation>			<responses>				<response>					<user_statement>Yes</user_statement>					<subsequent_question>0</subsequent_question>				</response>				<response>					<user_statement>No</user_statement>					<subsequent_question>9</subsequent_question>				</response>			</responses>		</statement><statement id="9" name="statement_09">			<statement_txt>Does that person live with you to provide care and assistance?</statement_txt>			<instantiation>				<invocatin>					<type>YesNo</type>				</invocatin>			</instantiation>			<responses>				<response>					<user_statement>Yes</user_statement>					<subsequent_question>0</subsequent_question>				</response>				<response>					<user_statement>No</user_statement>					<subsequent_question>11</subsequent_question>				</response>			</responses>		</statement></concessions>';

	var parser = new DOMParser();
	//xmlDoc = parser.parseFromString(text,"text/xml");

	$.xmlSource = {
		f:""
	};
	
	$.xmlSource.f = parser.parseFromString(concessions,"text/xml");
	
	//console.log(xmlToString($.xmlSource.f));
	$.statements = [];

	
	$('#concession_questionaire').accordion({
		heightStyle: "content",
		beforeActivate: function( event, ui ) {
		},	
		create: function( event, ui ) {
		},
		activate: function( event, ui ) { 
			console.log("activated");
		}
	});
	
	jQuery.fn.extend({
		insertNewStatement:function(){
			
			var tracker = [];
			function getStatement(_id){
				var output = {
					header:"",
					body:""
				};
				for (var i = 0; i < $.statements.length; i++){
					if ($.statements[i].id == _id){
						output.header = $.statements[i].header;
						output.body = $.statements[i].body;
						i = $.statements.length;
					}
				}
				return output;
			}
			
			console.log("Is active?: " + $(this)[0].state);
			//escape if already activated btn is clicked
			
			if ($(this)[0].state != "Active"){
				
				$(this)[0].state = "Active"
				$(this)[0].rnode.state = "Inactive";
				var pos = parseInt($(this)[0].getAttribute("title"));
				
				var subsequentH3s = document.evaluate('//div[@id = "concession_questionaire"]/h3', document.documentElement, null, XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null );
				var subsequentDIVs = document.evaluate('//div[@id = "concession_questionaire"]//div[@class = "form clearfix"]', document.documentElement, null, XPathResult.ORDERED_NODE_SNAPSHOT_TYPE, null);
				
				//Removing what is below by throwing pos as an argument to define the current position
				for ( var i = pos ; i < subsequentH3s.snapshotLength; i++ )
				{
					subsequentH3s.snapshotItem(i).parentNode.removeChild(subsequentH3s.snapshotItem(i));
				}

				for ( var i = pos ; i < subsequentDIVs.snapshotLength; i++ )
				{
					subsequentDIVs.snapshotItem(i).parentNode.removeChild(subsequentDIVs.snapshotItem(i));
				}					
				
				if ($(this)[0].data == 0){
					// 0 means the end of the process, and trigger an event that leads to a subsequent form data inputs by users
				} else {

					$( "input[name*='" + $(this).attr("name") + "']" ).val( $(this)[0].textContent);
					var newElement = getStatement($(this)[0].data);
					
					$('#concession_questionaire').append(newElement.header);
					$('#concession_questionaire').append(newElement.body);					
				}

				
			}
		}
	})
});

$(document).ready(function() {
	
	var state = "QLD"; // for stub only.  This needs to be replaced with an actural value passed through the price comparison process
	var parser = new DOMParser();
	
	var collection = [];

	var statements = $.xmlSource.f.getElementsByTagName("statement");
	for ( var i=0 ; i < statements.length; i++ )
	{
		collection.push(statements[i]);
		//console.log(statements[i]);
	}
		
	for (var i = 0; i < collection.length; i++){

		var div_5 = document.createElement("div");	
		div_5.setAttribute("class", "form clearfix");
		
		var div_6 = document.createElement("div");
		div_6.setAttribute("class", "form-data");
		
		if(collection[i].getElementsByTagName("type")[0].textContent == "YesNo"){

			
			var yesBtn = document.createElement("button");
			yesBtn.setAttribute("class","btn");
			yesBtn.setAttribute("name",collection[i].getAttribute("name"));
			yesBtn.setAttribute("title",i+1);
			yesBtn.appendChild(document.createTextNode("Yes"));
			yesBtn.setAttribute("onclick","$(this).insertNewStatement()");
			yesBtn.state = "Inactive";
			
			var noBtn = document.createElement("button");
			noBtn.setAttribute("class","btn");
			noBtn.setAttribute("name",collection[i].getAttribute("name"));
			noBtn.setAttribute("title",i+1);
			noBtn.appendChild(document.createTextNode("No"));
			noBtn.setAttribute("onclick","$(this).insertNewStatement()");
			noBtn.state = "Inactive";

			var h3 = document.createElement("h3");
			h3.setAttribute("class", "form-hdg");
			
			var div_03 = document.createElement("div");				
			div_03.setAttribute("class", "ffh-inr");
			div_03.appendChild(document.createTextNode(collection[i].getElementsByTagName("statement_txt")[0].textContent));
			
			h3.appendChild(div_03);

			var responses = $.xmlSource.f.evaluate('//statement[@id = "' + collection[i].getAttribute("id") + '"]/responses//response', $.xmlSource.f, null, XPathResult.UNORDERED_NODE_ITERATOR_TYPE, null );
			try {
				var thisNode = responses.iterateNext();

				while (thisNode) {
					if (thisNode.getElementsByTagName("user_statement")[0].textContent  == "Yes"){
						yesBtn.data = thisNode.getElementsByTagName("subsequent_question")[0].textContent;
					} else {
						noBtn.data = thisNode.getElementsByTagName("subsequent_question")[0].textContent;
					}
					thisNode = responses.iterateNext();
				}	
			}
			catch (e) {
				dump( 'Error: Document tree modified during iteration ' + e );
			}
			yesBtn.rnode = noBtn;	noBtn.rnode = yesBtn;

			var div_01 = document.createElement("div");
			var div_02 = document.createElement("div");	
			div_01.setAttribute("class", "checkBox mb20 ml10");
			div_02.setAttribute("class", "btn-group switch-btn clearfix");
			
			div_02.appendChild(yesBtn);
			div_02.appendChild(noBtn);
			div_01.appendChild(div_02);
			
			div_6.appendChild(div_01);
			div_5.appendChild(div_6);
			

			
			$.statements.push({
				header:h3,
				body:div_5,
				id:collection[i].getAttribute("id")
			});
		}
		
		/*
		if(collection[i].getElementsByTagName("type")[0].textContent == "Readout_script"){
			var h3 = document.createElement("h3");
			h3.setAttribute("class", "form-hdg");			
			collection[i].getElementsByTagName("type")[0].textContent
		}
		*/
	}
		//Preventing JQuery to dynamically bootstrap onclick event
		
		/*Commented out until having secured time to work on this 
		var accordionNode = document.getElementById("concession_questionaire");
		accordionNode.appendChild($.statements[0].header);
		accordionNode.appendChild($.statements[0].body);
		*/
});




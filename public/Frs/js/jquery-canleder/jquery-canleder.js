/*
* jQuery Canleder-Year-Month
* By: jichenghan  
* Version 0.1
* Last Modified: 11/07/2016
*/

(function($) {
	$.Canleder = function(box, options){
		var _canlederBox = "#Canleder_Year_Month";
		var _year_ul_li = ".year li";
		var _month_ul_li = ".month li";
		box = $(box);
		var box_height = parseFloat( box.height());
		var box_width = parseFloat( box.width());
		var boxOffset = box.offset();

		//add xl 2016-8-10
		var _season_ul_li = ".season li";
		var is_season = options.hasOwnProperty('is_season') ? options.is_season : false;
		//end xl 2016-8-10

		//add jch 2016-8-22
		var is_year = options.hasOwnProperty('is_year') ? options.is_year : false;
		//end jch 2016-8-22

		var canlederBox = null;
		box.click(function(){
			canlederBox = $(_canlederBox);
			if($(canlederBox).size() > 0){
				$(canlederBox).show();
			}else{
				_buildCanlederBox();
				$("body").append(canlederBox);

                $(document).click(function(e){
                    var pointX = e.pageX;
                    var pointY = e.pageY;
                    var $box  = canlederBox.data("box");

                    var isCanlederBox = $(e.target).parents(_canlederBox);

                    if(canlederBox.is(":visible") && $box && e.target != $box[0] && isCanlederBox.size() <= 0){
                        var offset = canlederBox.offset();
                        var top  = offset.top - 4;
                        var left  = offset.left - 4;
                        var height = top + parseFloat(canlederBox.outerHeight()) +  4;
                        var width = left + parseFloat(canlederBox.outerWidth()) + 4;
                        if(pointX > left && pointY > top &&
                                pointX < width && pointY < height){

                        }else{
                            canlederBox.hide();
                        }
                    }
                });
			}

			canlederBox.css({"top" : boxOffset.top + box_height + 6, "left": boxOffset.left});
			canlederBox.data("box", box); 

			_init();
		
		}); 


		

		function _init(){
			var now = new Date();
			var year = now.getFullYear();
			var month = now.getMonth() + 1;
			var season = Math.ceil(month / 4);
			if(box.val()){
				year = box.val().split("-")[0] * 1;
				month = box.val().split("-")[1] * 1;
				season = box.val().split("-")[1] * 1;
			}

			//console.log(year);
			//console.log(_getSelectYear(year));

			canlederBox.find(_year_ul_li).eq(1).find("div.inner").html(_getSelectYear(year));
			canlederBox.find(_year_ul_li).eq(1).find("div").append("<span class='inner_text'>年</span>");
			if(is_season)
			{
				canlederBox.find(_season_ul_li).eq(1).find("div.inner").html(_getSelectQuarter(season));
				canlederBox.find(_season_ul_li).eq(1).find("div").append("<span class='inner_text'>季</span>");
			}
			else
			{
				canlederBox.find(_month_ul_li).eq(1).find("div.inner").html(_getSelectMonth(month));
				canlederBox.find(_month_ul_li).eq(1).find("div").append("<span class='inner_text'>月</span>");
			}
		}

		function _buildCanlederBox(){
			canlederBox = $("<div/>");
			canlederBox.attr("id", "Canleder_Year_Month"); 
			
			_buildYear(canlederBox);

			//add jch 2016-8-22
			if(!is_year)
			{
				//add xl 2016-8-10
				if(is_season)
				{
					_buildQuarter(canlederBox);
				}
				else
				{
					_buildMonth(canlederBox);
				}
				//end xl 2016-8-10
			}
			//end jch 2016-8-22

			canlederBox.append($("<div/>").addClass("clearBoth"));
			_buildButton(canlederBox, is_year);
			
		};
		
		 
		function _buildYear(canlederBox){
			var $year =  $("<div/>").addClass("year").append("<ul/>").appendTo(canlederBox);
			var $year_ul = $year.find("ul");
			for(var i = 0; i < 3; i++){
				var $li = $("<li/>").append( $("<div/>").addClass("inner") );
				
				$li.hover(function(){
					$(this).addClass("over");	
				}, function(){
					$(this).removeClass("over");
				});

				$year_ul.append($li);
			}
			var $year_ul_li = $year_ul.find("li");

			$year_ul_li.eq(0).click(function(){
				var year = $select_year.val();
				canlederBox.find(_year_ul_li).eq(1).find("div.inner").html(_getSelectYear(--year));
			}).find("div.inner").text(" < ");

			$year_ul_li.eq(1).addClass("middle").click(function(){

			})
			.find("div.inner").addClass("paddingTop").html(_getSelectYear());

			$year_ul_li.eq(2).click(function(){
				var year = $select_year.val();
				canlederBox.find(_year_ul_li).eq(1).find("div.inner").html(_getSelectYear(++year));
			}).find("div.inner").text(" > ");
		};

		function _buildMonth(canlederBox){
			var $month =  $("<div/>").addClass("month").append("<ul/>").appendTo(canlederBox);
			var $month_ul = $month.find("ul");
			for(var i = 0; i < 3; i++){
				var $li = $("<li/>").append( $("<div/>").addClass("inner") );
				
				$li.hover(function(){
					$(this).addClass("over");	
				}, function(){
					$(this).removeClass("over");
				});

				$month_ul.append($li);
			}
			var $month_ul_li = $month_ul.find("li");

			$month_ul_li.eq(0).click(function(){
				var month = $select_month.val();
				canlederBox.find(_month_ul_li).eq(1).find("div.inner").html(_getSelectMonth(--month));
			}).find("div.inner").text(" < ");

			$month_ul_li.eq(1).addClass("middle").click(function(){
				
			})
			.find("div.inner").addClass("paddingTop").html(_getSelectMonth());

			$month_ul_li.eq(2).click(function(){
				var month = $select_month.val();
				canlederBox.find(_month_ul_li).eq(1).find("div.inner").html(_getSelectMonth(++month));
			}).find("div.inner").text(" > ");
		};

		//add xl 2016-8-10
		function _buildQuarter(canlederBox){
			var $season =  $("<div/>").addClass("season").append("<ul/>").appendTo(canlederBox);
			var $season_ul = $season.find("ul");
			for(var i = 0; i < 3; i++){
				var $li = $("<li/>").append( $("<div/>").addClass("inner") );
				
				$li.hover(function(){
					$(this).addClass("over");	
				}, function(){
					$(this).removeClass("over");
				});

				$season_ul.append($li);
			}
			var $season_ul_li = $season_ul.find("li");

			$season_ul_li.eq(0).click(function(){
				var season = $select_season.val();
				canlederBox.find(_season_ul_li).eq(1).find("div.inner").html(_getSelectQuarter(--season));
			}).find("div.inner").text(" < ");

			$season_ul_li.eq(1).addClass("middle").click(function(){
				
			})
			.find("div.inner").addClass("paddingTop").html(_getSelectQuarter());

			$season_ul_li.eq(2).click(function(){
				var season = $select_season.val();
				canlederBox.find(_season_ul_li).eq(1).find("div.inner").html(_getSelectQuarter(++season));
			}).find("div.inner").text(" > ");
		};
		//end xl 2016-8-10

		function _buildButton(canlederBox, is_year){
			var $button_confirm = $("<button/>").addClass("confirm").click(function(){
				canlederBox.data("box").val("");
				canlederBox.hide();
			}).text("确认");
			$button_confirm.hover(function(){
				$(this).addClass("over");	
			}, function(){
				$(this).removeClass("over");
			});
			$button_confirm.click(function(){  
				var year = canlederBox.find(_year_ul_li).eq(1).find("select").val();
				//add jch 2016-8-22
				if(is_year)
				{
					canlederBox.data("box").val(year);
				}
				else
				{
					//add xl 2016-8-10
					if(is_season)
					{
						var season = canlederBox.find(_season_ul_li).eq(1).find("select").val();
						var str = season < 10 ? "0" + season : season;

					}
					else
					{
						var month = canlederBox.find(_month_ul_li).eq(1).find("select").val();
						var str = month < 10 ? "0" + month : month;
					}
					canlederBox.data("box").val(year + "-" + str);
					//end xl 2016-8-10
				}
				//end jch 2016-8-22

				//add jch 2017-01-17
				$(document).find('#year_month input:first').val(year);
				$(document).find('#year_month input:eq(1)').val(str);
				//end jch 2017-01-17

				canlederBox.hide();
            });  
			var $button_clear = $("<button/>").addClass("clear").click(function(){
				canlederBox.data("box").val("");
				canlederBox.hide();
			}).text("清空");
			$button_clear.hover(function(){
				$(this).addClass("over");	
			}, function(){
				$(this).removeClass("over");
			});
			var $button = $("<div/>").addClass("button").append($button_confirm);
			canlederBox.append($button);
			var $button = $("<div/>").addClass("button").append($button_clear);
			canlederBox.append($button);
			
		};
		
		var $select = null;

		function _getSelectYear(year){
			//add xl 2016-8-11
			if(year == '')
			{
				year = new Date().getFullYear();
			}

			//end xl 2016-8-11
			
			
			$select_year = $("<select/>");
			//for(var i = 20; i >= 0; i--){
			//	$select_year.append($("<option/>").text(year + i ));
			//}
			for(var i = 0; i <= 30; i++){
				$select_year.append($("<option/>").text(year - i ));
			}
			$select_year.find("option").each(function(){
				if($(this).text() == year){
					$(this).attr("selected", "selected");
				}
			});
			return $select_year;
		};

		function _getSelectMonth(month){
			if(month == 0)
			{
				month = 12;
			}
			if(month == 13)
			{
				month = 1;
			}
			if(!month){
				month = new Date().getMonth() + 1;
			}
			
			$select_month = $("<select/>");
			for(var i = 1; i <= 12; i++){
				$select_month.append($("<option/>").text(i));
			}
			$select_month.find("option").each(function(){
				if($(this).text() == month){
					$(this).attr("selected", "selected");
				}
			});
			return $select_month;
		};
		//add xl 2016-8-10
		function _getSelectQuarter(season){
			if(season == 0)
			{
				season = 4;
			}
			if(season == 5)
			{
				monseasonth = 1;
			}
			if(!season){
				var month = new Date().getMonth() + 1;
				season = Math.ceil(month / 4);

				
			}
			
			$select_season = $("<select/>");
			for(var i = 1; i <= 4; i++){
				$select_season.append($("<option/>").text(i));
			}
			$select_season.find("option").each(function(){
				if($(this).text() == season){
					$(this).attr("selected", "selected");
				}
			});
			return $select_season;
		};

		//add xl 2016-8-10


	};

    $.fn.extend({
        Canleder: function(options) {
            options = $.extend({},options);
            this.each(function() {
				new $.Canleder(this, options);
			});
			return this;
        }
    });
    
})(jQuery);

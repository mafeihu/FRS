 $(function(){
    //鼠标下拉滚动
    $(".fixed-btn").hide();
    $(".fixed-btn-up").show();
    //头部点击换菜单事件
    // 首页点击事件
  $(".home-page").on('click',function(){
    $(".lt-nav").children().removeClass("bg_blue3");
    $(this).addClass("bg_blue3");
    $(".nav-1").addClass("none");
    $(".nav-l-none").removeClass("none");
  });
  //数据仪表
  $(".data-instrument").on('click',function(){
    $(".lt-nav").children().removeClass("bg_blue3");
    $(this).addClass("bg_blue3");
    $(".nav-1").addClass("none");
    $(".nav-l-two").removeClass("none");
  });
  //数据管理
  $(".data-manage").on('click',function(){
    $(".lt-nav").children().removeClass("bg_blue3");
    $(this).addClass("bg_blue3");
    $(".nav-1").addClass("none");
    $(".nav-l-three").removeClass("none");
  });
  //UCB
  $(".data-ucb").on('click',function(){
    $(".lt-nav").children().removeClass("bg_blue3");
    $(this).addClass("bg_blue3");
    $(".nav-1").addClass("none");
    $(".nav-l-four").removeClass("none");
  });
  
 // 控制弹框的高度
function resizeWidth(){
  $(".pop-up").each(function () {
    var h=window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
       $(this).css("max-height",""+(h-40)+"px");
  });
};
resizeWidth();
window.onresize = function(){
    resizeWidth();
    };
 var  footerHeight=function (){
           var h=window.innerHeight || document.documentElement.clientHeight || document.body.clientHeight;
            var height=$(".rt-content").height();
            var innerHeight=h-290;
            if ($('div').is('.rt-content')) {
                if(height<innerHeight){
                  // $(".rt-content").height(innerHeight);
                   $(".rt-content").css('min-height',innerHeight)
                };
              }else{
               // $(".rt-container").height(h-85); 
                $(".rt-container").css('min-height',h-85);
               $(".footer").addClass("abs-t");
             }
         };
footerHeight();

/* 点击按钮，下拉菜单在 显示/隐藏 之间切换 */
$(".dropbtn").click(function(){
  var that=$(this);
  $(".dropdown-content").each(function(){
       $(this).hide();
     });
  $(this).next().toggle("show");
  $(this).addClass("chageColor")
})
// 点击下拉菜单意外区域隐藏
window.onclick = function(event) {
  if (!event.target.matches('.dropbtn')) {
    $(".dropdown-content").hide();
  }
}  
//鼠标移开隐藏
 $(".dropdown").mouseleave(function(){
    $(this).find(".dropdown-content").hide();
    $(".dropbtn").removeClass("chageColor")
  });
//textarea高度自适应，随着内容增加高度增加
   $.fn.autoHeight = function(){    
        function autoHeight(elem){
            elem.style.height = 'auto';
            elem.scrollTop = 0; //防抖动
            elem.style.height = elem.scrollHeight + 'px';
        }
        this.each(function(){
            autoHeight(this);
            $(this).on('keyup', function(){
                autoHeight(this);
            });
        });     
    };
    $('textarea[autoHeight]').autoHeight();   
  });
var active_tab="",active_child_tab="";jQuery(document).ready(function($){$("h2.nav-tab-wrapper.needs-js").length>0&&($("h2.nav-tab-wrapper.needs-js").fadeIn("fast",function(){$(this).removeClass("needs-js")}),$("div.panel").hide(),active_tab=window.location.hash,0==active_tab.length?active_tab=$("h2.nav-tab-wrapper a.nav-tab-active").attr("href"):($("h2.nav-tab-wrapper a.nav-tab-active").removeClass("nav-tab-active"),$('h2.nav-tab-wrapper a[href="'+active_tab+'"]').addClass("nav-tab-active")),$(active_tab+"-panel").show(),$("h2.nav-tab-wrapper").on("click","a",function(a){a.preventDefault(),$("h2.nav-tab-wrapper a").removeClass("nav-tab-active"),$("div.panel").hide(),$(this).addClass("nav-tab-active"),active_tab=$(this).attr("href"),$(active_tab+"-panel").show(),history.pushState?history.pushState(null,null,$(this).attr("href")):location.hash=$(this).attr("href")}))});
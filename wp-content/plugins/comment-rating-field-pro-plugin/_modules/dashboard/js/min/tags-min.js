jQuery(document).ready(function($){var t=function(){$("select.wpcube-tags").each(function(){$(this).unbind("change.wpcube-tags").on("change.wpcube-tags",function(t){var n=$(this).val(),e=$(this).data("element"),a=$(e).val(),c=$(e)[0].selectionStart;c>0&&(n=" "+n),$(e).val(a.substring(0,c)+n+a.substring(c))})})};t()});
window.resetForm=function(r){document.getElementById(r).reset()},window.toggleModal=function(r){var e=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"show";$("#".concat(r)).modal(e)},window.validateForm=function(r){for(var e,o,n,i=arguments.length>1&&void 0!==arguments[1]?arguments[1]:"error",d=$("#"+r+" .form-control"),t=!0,a=0;a<d.length;a++)o=d[a].id,e=d[a].value,o&&("error"==i?""!=e&&e?($("#"+o).removeClass("is-invalid"),$("#"+o+"_err").html("")):(n=$("#"+o).data("name"),$("#"+o).addClass("is-invalid"),$("#"+o+"_err").html(n+" field is required"),t=!1):($("#"+o).removeClass("is-invalid"),$("#"+o+"_err").html("")));return t},window.addZero=function(r){var e=r;return r<10&&(e="0"+r),e};
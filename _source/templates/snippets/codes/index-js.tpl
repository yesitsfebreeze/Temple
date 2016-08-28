lang coffeescript

listen = (el, event, handler) ->
    if el.addEventListener
        el.addEventListener event, handler
    else
        block event
            el.attachEvent 'on' + event, ->
                handler.call el
                include example.js
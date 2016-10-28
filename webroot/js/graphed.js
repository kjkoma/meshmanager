window.graphed = {};
window.graphed.host = 'graphed.japacom.co.jp';
/**
 * file name: graphed.js
 * author: JCS.Aung
 * description: This will be embedded in user web site.
 *          This script will support to create cloud graph in sending data.
 */

(function( global, undefined ) {

    // graphed object will be create by dynamic see in app.js
    // client

    var API_HOST, API_URL;

    var graphed;
    var messageHandlers = {};
    var docReadyListeners = [];
    var isDocReady = false;

    graphed = global.graphed = global.graphed || {};

    API_HOST = window.location.protocol + '//' + graphed.host;
    API_URL  = API_HOST + '/embed';

    if ( document.readyState === 'complete' ) {

        isDocReady = true;
        setTimeout( docReady );

    } else {

        document.addEventListener( 'DOMContentLoaded', completed, false );
        window.addEventListener( 'load', completed, false );
    }

    window.addEventListener( 'message', onMessage, false );

    function completed() {

        document.removeEventListener( 'DOMContentLoaded', completed, false );
        window.removeEventListener( 'load', completed, false );
        docReady();
    }

    function onMessage( evt ) {

        var data, cb;

        if ( evt.origin !== API_HOST ) {

            return;
        }

        try {

            data = JSON.parse( evt.data );
        } catch ( ex ) {

            data = evt.data;
        }

        if ( data.id ) {

            cb = messageHandlers[data.id];

            if ( cb ) {

                setTimeout(function() {

                    cb( evt, data );
                });
            }
        }
    }

    function docReady() {

        var listener;

        while ( docReadyListeners.length ) {

            listener = docReadyListeners.shift();

            typeof listener === 'function' && listener();
        }

        createNoCodeStaticGraph();
    }

    function createNoCodeStaticGraph() {

        var containerId, key, loader, scripts, src, qs, pks, i;

        scripts = document.getElementsByTagName( 'script' );

        for ( i = 0; i < scripts.length; i++ ) {

            if ( scripts.item( i ).src.indexOf( 'graphed.js' ) > 0 ) {

                src = scripts.item( i ).src;
                break;
            }
        }

        if ( !src ) {

            return;
        }

        qs = parseQS( src.indexOf( '?' ) > 1 ? src.split( '?' )[1] : '' );
        pks = (qs != null ? qs.pks : undefined);

        // コーディングフリー静的グラフを作成
        // グラフの配置する要素IDとキーをgraphed.jsのQSとして指定（複数あった場合ははカンマ区切り）
        if ( !pks || pks.length < 1 ) {

            return;
        }

        pks.split( ',' ).forEach(function( v ) {

            containerId = decodeURIComponent( v.split( ':' )[0] );
            key = decodeURIComponent( v.split( ':' )[1] );

            loader = graphed.load({
                containerId: containerId,
                key: key
            });
        });
    }

    function addMessageListener( id, cb ) {

        cb = typeof( cb ) === 'function' ? cb : null;

        messageHandlers[id] = cb;
    }

    graphed.load = function( opt ) {

        var loader = {};
        var records = [];
        var frameReadyTasks = [];
        var targetWindow = null;
        var selector, key;

        opt = typeof( opt ) !== 'object' ? {} : opt;

        selector = opt.containerId;
        key = opt.key;

        if ( !selector || !key ) {

            throw 'Wrong option was given.';
        }

        loader.addData = function( record ) {

            records.push( record );
        };

        loader.clearData = function() {

            records.length = 0;
        };

        loader.draw = function() {

            var ele = searchEle( selector );

            if ( !ele ) {

                throw 'Target dom element cannot found.';
            }

            onFrameReady(function() {

                // console.log( 'onFrameReady' );
                // console.log( 'draw records', records );

                messageTo( targetWindow, {
                    data: {
                        records: records
                    },
                    type: 'draw'
                });

                records.length = 0;
            });
        };

        loader.destroy = function() {

        };

        function addPlaceHolder() {

            var frame, url, id;
            var ele = searchEle( selector );

            if ( !ele ) {

                throw 'Target dom element cannot found.';
            }

            id = graphed.newId();
            url = [
                API_URL,
                '?id=' + id,
                '&key=' + key
            ].join( '' );

            ele.setAttribute( 'data-frame-id', id );

            addMessageListener( id, onMessage );

            frame = document.createElement( 'iframe' );
            frame.frameBorder = '0';
            frame.width = '100%';
            frame.height = '100%';
            frame.scrolling = 'no';
            frame.src = url;

            // clear old content
            ele.innerHTML = '';
            ele.appendChild( frame );
        }

        function onMessage( evt, data ) {

            if ( data.type === 'ready' ) {

                // 対象Windowが用意出来た
                targetWindow = evt.source;
                execTasks();
                return;
            }
        }

        function onFrameReady( cb ) {

            frameReadyTasks.push( checkCallback( cb ) );

            if ( targetWindow ) {

                // this.execTasks();
                execTasks();
            }
        }

        function execTasks() {

            var cb;

            while ( frameReadyTasks.length ) {

                cb = frameReadyTasks.shift();

                cb();
            }
        }

        addPlaceHolder();

        return loader;
    };

    graphed.newId = (function() {

        var ts = new Date().getTime();

        return function() {

            ts = ts + 1;
            return ts;
        };
    })();

    function messageTo( win, data ) {

        data = typeof( data ) === 'object' ? JSON.stringify( data ) : data;

        win.postMessage( data, '*' );
    }

    function checkCallback( cb ) {

        return typeof( cb ) === 'function' ? cb : function() {};
    }

    function searchEle( selector ) {

        var ele = null;

        if ( document.querySelector ) {

            // search by querysSelector
            ele = document.querySelector( selector ) || document.querySelector( '#' + selector );

        } else {

            ele = document.getElementById( selector.indexOf( '#' ) ? selector.slice( 1 ) : selector );
        }

        return ele;
    }

    function parseQS( qs ) {

        var ret = {};

        if ( !qs || qs.length === 0 ) {

            return null;
        }

        qs = qs.indexOf( '?' ) === 0 ? qs.slice( 1 ) : qs;

        qs.split( '&' ).forEach(function( kv ) {

            var kvs = kv.split( '=' );
            var k = kvs.length > 0 ? kvs[0] : '';
            var v = kvs.length > 1 ? kvs[1] : '';

            ret[k] = v;
        });

        return ret;
    }
})( window );

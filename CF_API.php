<?php
class CF_API {
    public function __construct() {
        $this->data['email'] = CF_API_EMAIL;
        $this->data['auth_token'] = CF_API_AUTH_TOKEN;
        $this->url = CF_API_URL;
    }
    public function get_funnels() {
        $url = $this->url.'funnels.json?';
        $data = $this->data;
        $query = http_build_query( $data );
        if ( get_option( 'clickfunnels_api_email' ) == "" || get_option( 'clickfunnels_api_auth' ) == "" ) {
        } else {
            $new_url = $url.$query;
            $content = file_get_contents( $new_url );
            $funnels = json_decode( $content );
        }
        return $funnels;
    }
    public function get_page_html( $funnel_id, $position = 0, $meta = "" ) {
        $newHTML = '<!DOCTYPE html>
<html>
<head>
    <!-- Powered by ClickFunnels.com -->
    <script>
        function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        }
        $preview = getParameterByName("preview");
        console.log($preview);
        if( $preview == "" ){
          // do nothing
        } else if( $preview == "control" ){
          document.cookie = "lander-ab-tests-240861=lander_control";
        } else{
          document.cookie = "lander-ab-tests-240861=lander_" + $preview;
        }
    </script>
    <link href="https://assets3.clickfunnels.com/assets/lander.css" media="screen" rel="stylesheet"/>
    <script type="text/javascript" src="https://static.clickfunnels.com/clickfunnels/landers/tmp/'.$position.'.js"></script>
    '.urldecode( $meta ).'
    <style>#IntercomDefaultWidget{display:none;}#Intercom{display:none;}</style>
    <meta name="nodo-proxy" content="html"/>
</head>
<body>
</body>
</html>';
        return $newHTML;
    }
}

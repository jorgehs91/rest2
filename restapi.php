<?php
$url = null;

/* 
*   LOGIN
*/
// $post = [
//     'email' => 'teste@teste.com',
//     'pwd' => 'teste'
// ];
// $url = 'http://localhost/rest2/rest/login';

/* 
*   INSERT
*/
// $post = [
//     'email' => 'jorge@teste.com',
//     'pwd' => 'jorge',
//     'table' => 'users',
//     'data'    =>  array('user_fullname' => 'jorge', 'password' => md5('jorge'), 'email' => 'jorge@teste.com', 'user_status' => '2')
// ];
// $url = 'http://localhost/rest2/rest/Insert';

/* 
*   UPDATE
*/
// $post = [
//     'email' => 'jorge@teste.com',
//     'pwd' => 'jorge',
//     'table' => 'users',
//     'primary_key' => array('id' => 4), 
//     'data'    =>  array('user_fullname' => 'Summa edited', 'password' => md5('123'), 'email' => 'second@kvcodes.com', 'user_status' => '1')
// ];
// $url = 'http://localhost/rest2/rest/Update';

/* 
*   GET ROW
*/
// $post = [
//     'email' => 'jorge@teste.com',
//     'pwd' => 'jorge',
//     'table' => 'users',
//     'conditions' => array('id' => 17)
// ];
// $url = 'http://localhost/rest2/rest/GetRow';

/* 
*   GET VALOR DO CAMPO
*/
// $post = [
//     'email' => 'jorge@teste.com',
//     'pwd' => 'jorge',
//     'table' => 'users',
//     'column_single' => 'user_fullname',
//     'conditions' => array('id' => 17),
// ];
// $url = 'http://localhost/rest2/rest/GetSingleValue';

/* 
*   GET TODAS AS OCORRENCIAS ATRAVÉS DA CONDIÇÃO
*/
// $post = [
//     'email' => 'jorge@teste.com',
//     'pwd' => 'jorge',
//     'table' => 'users',
//     'conditions' => array('user_status' => 1)
// ];
// $url = 'http://localhost/rest2/rest/GetAll';

/* 
*   DELETE ROW ATRAVÉS DA CONDIÇÃO
*/
// $post = [
//     'email' => 'jorge@teste.com',
//     'pwd' => 'jorge',
//     'table' => 'users',
//     'conditions' => array('id' => 4)
// ];
// $url = 'http://localhost/rest2/rest/Delete';

?>

<button class="insert">INSERT</button>
<button class="update">UPDATE</button>
<button class="getRow">GET ROW</button>
<button class="getValue">GET VALUE</button>
<button class="getAll">GET ALL</button>
<button class="delete">DELETE</button>

<?php
if($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($post));
    $response = curl_exec($ch); 
    
    var_export($response); 
}
?>

<script>
    function insert(){
        
    }
</script>
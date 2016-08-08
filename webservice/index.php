<?php
/**
 * Created by PhpStorm.
 * User: andre
 * Date: 18/04/16
 * Time: 20:10
 */

ini_set('display_errors', 1);
ini_set('display_startup_erros', 1);
error_reporting(E_ALL);
require __DIR__."/vendor/autoload.php";
$app = new \Slim\Slim();

$app->add(new \Slim\Middleware\SessionCookie(array('secret' => 'myappsecret')));
$requireLogin = function ($app) {
    return function () use ($app) {
        if (!isset($_SESSION['user'])) {
            $app->halt(401);
        }
    };
};
$requireLvl0 = function ($app) {
    return function () use ($app) {
        if($_SESSION['user']['lvl']>0){
            $app->halt(403);
        }
    };
};
$app->hook('slim.before.dispatch', function() use ($app) {
    $user = null;
    if (isset($_SESSION['user'])) {
        $user = $_SESSION['user'];
    }
    $app->view()->setData('user', $user);
});
$app->post('/login', function () use ($app) {
    $app->response()->header('Content-Type', 'application/json');
    $input=json_decode($app->request()->getBody(),true);
    $con=new \dynastyking\database\Connection();
    $result=$con->selectQuery("SELECT id, nome,sobrenome, email, lvl FROM user WHERE email=:email AND senha=:senha",array(":email"=>$input['email'],":senha"=>sha1($input['senha'])));
    if(count($result)==1){
        $_SESSION['user']=$result[0];
        $app->response()->status(200);
        echo json_encode($result[0]);
    } else {
        $app->response()->status(401);
    }
});
$app->post('/logout', function () use ($app) {
    $app->response()->header('Content-Type', 'application/json');
    unset($_SESSION['user']);
    $app->response()->status(200);
});

//Produto
$app->get('/produto', $requireLogin($app), function () use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $con=new \dynastyking\database\Connection();
    $produto=new \web2\Produto();
    echo json_encode($produto->select($con));
});
$app->get('/produto/:id', $requireLogin($app), function ($id) use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $con=new \dynastyking\database\Connection();
    $produto=new \web2\Produto();
    $produto->valorpk=$id;
    echo json_encode($produto->select($con));
});
$app->post('/produto', $requireLogin($app), function () use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $input=json_decode($app->request()->getBody(),true);
    $con=new \dynastyking\database\Connection();
    $produto=new \web2\Produto();
    $produto->setValor("descricao",$input['descricao']);
    $produto->setValor("qtd",$input['qtd']);
    $produto->setValor("preco",$input['preco']);
    $produto->insert($con);
});
$app->post('/produto/:id', $requireLogin($app), function ($id) use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $input=json_decode($app->request()->getBody(),true);
    $con=new \dynastyking\database\Connection();
    $produto=new \web2\Produto();
    $produto->valorpk=$id;
    $produto->setValor("descricao",$input['descricao']);
    $produto->setValor("qtd",$input['qtd']);
    $produto->setValor("preco",$input['preco']);
    $produto->update($con);
});
$app->delete('/produto/:id', $requireLogin($app), function ($id) use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $con=new \dynastyking\database\Connection();
    $produto=new \web2\Produto();
    $produto->valorpk=$id;
    $produto->delete($con);
});

//Compra
$app->get('/compra', $requireLogin($app), function () use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $con=new \dynastyking\database\Connection();
    $compra=new \web2\Compra();
    echo json_encode($compra->select($con));
});
$app->get('/compra/:id', $requireLogin($app), function ($id) use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $con=new \dynastyking\database\Connection();
    $compra=new \web2\Compra();
    $compra->valorpk=$id;
    echo json_encode($compra->select($con));
});
$app->post('/compra', $requireLogin($app), function () use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $input=json_decode($app->request()->getBody(),true);
    $con=new \dynastyking\database\Connection();
    $compra=new \web2\Compra();
    $compra->setValor("cliente_id",$input['cliente_id']);
    $compra->setValor("valor_total",$input['valor_total']);
    $compra->setValor("status",$input['status']);
    $compra->insert($con);
});
$app->post('/compra/:id', $requireLogin($app), function ($id) use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $input=json_decode($app->request()->getBody(),true);
    $con=new \dynastyking\database\Connection();
    $compra=new \web2\Compra();
    $compra->valorpk=$id;
    $compra->setValor("cliente_id",$input['cliente_id']);
    $compra->setValor("valor_total",$input['valor_total']);
    $compra->setValor("status",$input['status']);
    $compra->update($con);
});
$app->delete('/compra/:id', $requireLogin($app), function ($id) use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $con=new \dynastyking\database\Connection();
    $compra=new \web2\Compra();
    $compra->valorpk=$id;
    $compra->delete($con);
});

//Cliente
$app->get('/cliente', $requireLogin($app), function () use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $con=new \dynastyking\database\Connection();
    $cliente=new \web2\Cliente();
    echo json_encode($cliente->select($con));
});
$app->get('/cliente/:id', $requireLogin($app), function ($id) use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $con=new \dynastyking\database\Connection();
    $cliente=new \web2\Cliente();
    $cliente->valorpk=$id;
    echo json_encode($cliente->select($con));
});
$app->post('/cliente', function () use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $input=json_decode($app->request()->getBody(),true);
    $con=new \dynastyking\database\Connection();
    $cliente=new \web2\Cliente();
    $cliente->setValor("nome",$input['nome']);
    $cliente->setValor("sobrenome",$input['sobrenome']);
    $cliente->setValor("email",$input['email']);
    $cliente->setValor("senha",sha1($input['senha']));
    $cliente->insert($con);
});
$app->post('/cliente/:id', $requireLogin($app), function ($id) use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $input=json_decode($app->request()->getBody(),true);
    $con=new \dynastyking\database\Connection();
    $cliente=new \web2\Cliente();
    $cliente->valorpk=$id;
    $cliente->setValor("nome",$input['nome']);
    $cliente->setValor("sobrenome",$input['sobrenome']);
    $cliente->setValor("email",$input['email']);
    $cliente->delCampo("senha");
    $cliente->update($con);
});
$app->delete('/cliente/:id', $requireLogin($app), function ($id) use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $con=new \dynastyking\database\Connection();
    $cliente=new \web2\Cliente();
    $cliente->valorpk=$id;
    $cliente->delete($con);
});

//CompraProduto
$app->get('/compraproduto', $requireLogin($app), function () use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $con=new \dynastyking\database\Connection();
    $compraproduto=new \web2\CompraProduto();
    echo json_encode($compraproduto->select($con));
});
$app->get('/compraproduto/:id', $requireLogin($app), function ($id) use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $con=new \dynastyking\database\Connection();
    $compraproduto=new \web2\CompraProduto();
    $compraproduto->valorpk=$id;
    echo json_encode($compraproduto->select($con));
});
$app->post('/compraproduto', $requireLogin($app), function () use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $input=json_decode($app->request()->getBody(),true);
    $con=new \dynastyking\database\Connection();
    $compraproduto=new \web2\CompraProduto();
    $compraproduto->setValor("compra_id",$input['compra_id']);
    $compraproduto->setValor("produto_id",$input['produto_id']);
    $compraproduto->setValor("quantidade",$input['quantidade']);
    $compraproduto->insert($con);
});
$app->post('/compraproduto/:id', $requireLogin($app), function ($id) use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $input=json_decode($app->request()->getBody(),true);
    $con=new \dynastyking\database\Connection();
    $compraproduto=new \web2\CompraProduto();
    $compraproduto->valorpk=$id;
    $compraproduto->setValor("compra_id",$input['compra_id']);
    $compraproduto->setValor("produto_id",$input['produto_id']);
    $compraproduto->setValor("quantidade",$input['quantidade']);
    $compraproduto->update($con);
});
$app->delete('/compraproduto/:id', $requireLogin($app), function ($id) use ($app) {
    $app->response()->status(200);
    $app->response()->header('Content-Type', 'application/json');
    $con=new \dynastyking\database\Connection();
    $compraproduto=new \web2\CompraProduto();
    $compraproduto->valorpk=$id;
    $compraproduto->delete($con);
});
$app->run();
<?php


namespace App;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class ReviewsController {
    public ReviewStore $reviewStoreComp;

    public function __construct(){
        $this->reviewStoreComp = new ReviewStore();
    }

    public function getReview(Request $request, Response $response, $args): Response {
        $idReview = $args['id'];

        $result = $this->reviewStoreComp->getReviewById((int)$idReview);
        if ($result===null) {
            $result = ['status'=>'value not found'];
        }
        $reviewJSON = json_encode($result, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($reviewJSON);

        return $response->withHeader('Content-Type', 'application/json');;
    }

    public function getReviews(Request $request, Response $response, $args){
        $queryParams = $request->getQueryParams();
        $page = (int) $queryParams['page'] ?: 1;

        $reviews = $this->reviewStoreComp->getAllReviews($page);
        $encodedReviews = json_encode($reviews, JSON_UNESCAPED_UNICODE);

        $response->getBody()->write($encodedReviews);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function addReview(Request $request, Response $response, $args){
        $contentType = $request->getHeaderLine('Content-Type');

        if (strstr($contentType, 'application/json')){
            $contentJSON = $request->getBody()->getContents();
            $content = json_decode($contentJSON,JSON_UNESCAPED_UNICODE);

            if(empty($content)){
                $report = ['report'=>'Переданы пустые поля'];
            }else{
                if (array_key_exists('usernameKey',$content) && array_key_exists('userReviewKey',$content)){
                    $responseStore = $this->reviewStoreComp->addNewReview($content['usernameKey'], $content['userReviewKey']);
                    $report = ['status'=>'Отзыв успешно добавлен', $responseStore ];
                }else{
                    $report = ['status'=>'Не переданы ключи'];
                }
            }
        }else{
            $report = ['status'=>'Неверный формат данных'];
        }
        $report = json_encode($report, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($report);
        return $response->withHeader('Content-Type', 'application/json');
    }

    public function removeReview(Request $request, Response $response, $args){
        $reviewId = (int)$args['id'];

        $result = $this->reviewStoreComp->deleteReview($reviewId);
        $result = json_encode($result, JSON_UNESCAPED_UNICODE);
        $response->getBody()->write($result);
        return $response->withHeader('Content-Type', 'application/json');


    }
}
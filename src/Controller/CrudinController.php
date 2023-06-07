<?php

namespace Drupal\crudin\Controller;


use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection;
use Drupal\Core\Database\Database;
use Drupal\Core\Url;
use Drupal\Core\Link;
use Drupal\Core\Messenger;
use Symfony\Component\DependencyInjection\ContainerInterface;


class CrudinController extends ControllerBase {

  public function __construct(private readonly Connection $database) {
  }

  public static function create(ContainerInterface $container) {
    return new static (
      $container->get('database')
    );
  }


  public function Listing() {

    $header_table = [
      'id' => t('ID'),
      'name' => t('Name'),
      'age' => t('Age'),
      'opt' => t('Operation'),
      'opt1' => t('Operation'),
    ];
    $row = [];

    $query = $this->database->select('crudin', 'm');
    $query->fields('m', ['id', 'name', 'age']);
    $result = $query->execute()->fetchALL();

    foreach ($result as $value) {
      $delete = Url::fromRoute('crudin.crudin_form', ['id' => $value->id]);
      $edit = Url::fromRoute('crudin.crudin_form', ['id' => $value->id]);

      $row[] = [
        'id' => $value->id,
        'name' => $value->name,
        'age' => $value->age,
        'opt' => Link::fromTextAndUrl('Edit', $edit)->toString(),
        'opt1' => Link::fromTextAndUrl('Delete', $delete)->toString(),
      ];
    }

    $add = Url::fromUserInput('/crudin/form/data');

    $text = "Add User";

    $data['table'] = [
      '#type' => 'table',
      '#header' => $header_table,
      '#rows' => $row,
      '#empty' => t('No Record Found'),
      '#caption' => Link::fromTextAndUrl($text, $add)->toString(),
    ];

    $this->messenger()->addMessage('Record Listed');

    return $data;
  }

}

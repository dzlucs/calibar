<?php

namespace App\Services;

use Core\Constants\Constants;
use Core\Database\ActiveRecord\Model;

class DrinkGallery
{
   /** @var array<string, mixed> $image */
   private array $image; //arquivo que user enviou no form

   public function __construct(
      private Model $model,
      /** @var array<string, string|array<string>> $validations */
      private array $validations = []
   ) {  
   }

   /* public function path(): string
   {
      if($this->model->)
   } */

   public function create(array $image): bool
   {
      $this->image = $image;

      if (!$this->isValidImage()){
         return false;
      }

      return $this->createFile(); // será implementado
   }

/*    public function createFile(): bool
   {
      
   } */

   //Garantindo que nenhuma imagem tenha nome repetido e sobreescreva outra imagem
   public function getFileName(): string
   {

      $file_name = pathinfo($this->image['name'], PATHINFO_FILENAME);
      $file_extension = pathinfo($this->image['name'], PATHINFO_EXTENSION);
      $unique_id = uniqid();

      return "{$file_name}_{$unique_id}.{$file_extension}";
   }

   private function isValidImage(): bool
   {
      if (isset($this->validations['extension'])) {
         $this->validateImageExtension();
      }

      if (isset($this->validations['size'])) {
         $this->validateImageSize();
      }

      return $this->model->errors('image_name') === null; //true se não tiver erros
   }

   private function validateImageExtension(): void
   {
      $file_name_splitted = explode('.', $this->image['name']); 
      //ex.: [file.png] -> ['file', 'png']
      $file_extension = end($file_name_splitted); //extensão do arquivo

      if (!in_array($file_extension, $this->validations['extension'])) {
         $this->model->addError('image_name', 'Formato de imagem não suportado!');
      }
   }

   private function validateImageSize(): void
   {
      if ($this->image['size'] > $this->validations['size']) {
         $this->model->addError('image_name', 'A imagem deve ter no máximo 2MB!');
      }
   }
}

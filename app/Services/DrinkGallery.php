<?php

namespace App\Services;

use App\Models\Drink;
use Core\Constants\Constants;
use Core\Database\ActiveRecord\Model;
use RuntimeException;

class DrinkGallery
{
   /**
    * @var array<string, mixed> $image
    * @var string $savedFileName
    */

   private ?string $savedFileName = null;
   private array $image = [];

    public function __construct(
        private Drink $model,
        /** @var array<string, string|array<string>> $validations */
        private array $validations = []
    ) {
    }

    public function create(array $image): bool
    {
        $this->image = $image;

        if (!$this->isValidImage()) {
            return false;
        }

        $this->savedFileName = $this->generateUniqueFileName();

        return $this->storeNewImage();
    }

    public function storeNewImage(): bool
    {
        if (empty($this->getTmpFilePath())) {
            return false;
        }

        $moved = move_uploaded_file(
            $this->getTmpFilePath(),
            $this->getAbsoluteDestinationPath()
        );

        if (!$moved) {
            $error = error_get_last();
            throw new \RuntimeException(
                'Failed to move uploaded file: ' . ($error['message'] ?? 'Unknown error')
            );
        }

       //criando uma nova instância da imagem
        $newImage = $this->model->images()->new([
         'drink_id'   => $this->model->id,
         'image_name' => $this->savedFileName
        ]);

       //criando o registro da imagem no banco e retornando o status
        return $newImage->save();
    }
   
    public function path(string $img): string
    {
        return $this->baseDir() . $img;
    }

   //monta o caminho relativo
    public function baseDir(): string
    {
        return "/assets/uploads/{$this->model::table()}/{$this->model->id}/";
    }

    public function absoluteBaseDir(): string
    {
     // caminho físico no sistema de arquivos (usado em unlink(), mkdir(), etc)
        return __DIR__ . "/../../public" . $this->baseDir();
    }

   //constrói o caminho absoluto no servidor
    public function storeDir(): string
    {
        $path = Constants::rootPath()->join('public' . $this->baseDir());
        if (!is_dir($path)) {
            mkdir(directory: $path, recursive: true);
        }

        return $path;
    }

   //retorna caminho absoluto final da imagem no servidor - local exato onde será salvo
    public function getAbsoluteDestinationPath(): string
    {
        return $this->storeDir() . $this->savedFileName;
    }

   //Garantindo que nenhuma imagem tenha nome repetido e sobreescreva outra imagem
    public function generateUniqueFileName(): string
    {

        $file_name = pathinfo($this->image['name'], PATHINFO_FILENAME);
        $file_extension = pathinfo($this->image['name'], PATHINFO_EXTENSION);
        $unique_id = uniqid();

        return "{$file_name}_{$unique_id}.{$file_extension}";
    }

    public function getTmpFilePath(): string
    {
        return $this->image['tmp_name'];
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

    public function firstSavedImagePath(): string
    {
        $images = $this->model->images()->get();

        if (!empty($images)) {
            $first = $images[0];
            return $this->baseDir() . $first->image_name;
        }

        return "/assets/images/defaults/drink-1.jpeg";
    }

    public function destroyAllImages(): void
    {

        $images = $this->model->images()->get();
        $dirPath = $this->storeDir();

        foreach ($images as $image) {
            $path = $dirPath . $image->image_name;

            if (file_exists($path)) {
                unlink($path);
            }
        }


       // garantir que o diretório está vazio antes de remover
        if (is_dir($dirPath)) {
            $files = glob($dirPath . '*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
        }

        rmdir($dirPath);
    }
}
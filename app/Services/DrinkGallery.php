<?php

namespace App\Services;

use App\Models\DrinkImage;
use App\Models\Drink;
use Core\Constants\Constants;
use Core\Database\ActiveRecord\Model;
use RuntimeException;

class DrinkGallery
{
   /**
    * @var string $savedFileName
    */
    private ?string $savedFileName = null;

    /**
     * @var array<string, mixed> $image
     */
    private array $image = [];

    public function __construct(
        private DrinkImage $model,
        /** @var array<string, string|int|array<string>> $validations */
        private array $validations = []
    ) {
    }

    /** @param array<string, mixed> $image */
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
        /* $newImage = new DrinkImage([
         'drink_id'   => $this->model->id,
         'image_name' => $this->savedFileName
        ]); */

        //ao invés de criar, apenas atualizo o DrinkImage já criado lá no controller
        $this->model->image_name = $this->savedFileName;


       //criando o registro da imagem no banco e retornando o status
        return $this->model->save();
    }

    public function path(): string
    {
        return $this->baseDir() . $this->model->image_name;
    }

   //monta o caminho relativo
    public function baseDir(): string
    {
        return "/assets/uploads/drinks/{$this->model->drink_id}/";
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

    public function isValidImage(): bool
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

    public function destroyDrinkImage(): bool
    {
        $path = $this->absoluteBaseDir() . $this->model->image_name;

        if (!file_exists($path)) {
            return false;
        }

        unlink($path);

        if (!$this->model) {
            return false;
        }

        $this->model->destroy();

        return true;
    }

    public static function destroyAllDrinkImages(string $id): bool
    {
        $dir = (string) Constants::rootPath()->join('public' . '/assets/uploads/drinks/' . $id);

        if (is_dir($dir)) {

            $files = glob($dir . '/*');

            foreach ($files as $file){
                if(is_file($file)){
                    unlink($file);
                }  
            }

            if(count(glob($dir . '/*')) === 0){
                rmdir($dir);
                return true;
            }
        }
        return false;
    }
}

<?php

namespace App\Service;

use Exception;
use Pimcore\Model\DataObject\Product;

class ProductService
{
    private array $error;

    /**
     * @param Product $product
     * @param $form
     * @param int $id
     * @return bool
     */
    public function addProduct(Product $product, $form, int $id = 0): bool
    {
        $result = false;

        try {
            if ($form->isSubmitted() && $form->isValid()) {
                $product->setKey($product->getName());
                $this->setParentId($product);
                if ($id === 0) { // Add new product
                    $this->setProductSetter ($product, $form->all());
                    $product->setPublished(true);
                    $product->save();
                    $productId = $product->getId();
                    $savedProduct = Product::getById($productId);
                    if (!$savedProduct) {
                        throw new Exception("New product saving failed.");
                    }
                } else { // Update product
                    $product->save();
                    $product->setPublished(true);
                }
                $result = true;
            } else {
                throw new Exception('Form is not valid:' . $form->getErrors());
            }

        } catch (Exception $e) {
            $this->setError(['Failed to save product', $e->getMessage()]);
        }
        return $result;
    }

    /**
     * @param array $error
     */
    public function setError(array $error): void
    {
        $this->error = $error;
    }

    /**
     * @return string
     */
    public function getError(): string
    {
        return implode(' ,', $this->error);
    }

    /**
     * @param Product $product
     * @return void
     */
    private function setParentId (Product $product): void
    {
        $parentFolderId = 1;
        $parent = $product->getParent();
        if($parent) {
            $parentFolderId = $product->getParent()->getId() ?? 1;
        }
        $product->setParentId($parentFolderId);
    }

    /**
     * @param Product $product
     * @param $formFields
     * @return void
     */
    private function setProductSetter (Product $product, $formFields): void
    {
        foreach ($formFields as $fieldName => $field) {
            $setter = 'set' . ucfirst($fieldName);
            if (method_exists($product, $setter)) {
                $product->$setter($field->getData());
            }
        }
    }
}

<?php

namespace App\Service;

use App\Entity\Product;
use App\Repository\ProductRepository;
use App\Service\Core\IProductService;
use App\Util;
use Doctrine\ORM\Cache\Exception\FeatureNotImplemented;
use Exception;

class ProductService implements IProductService
{
    private readonly ProductRepository $productRepository;
    public function __construct(ProductRepository $productRepository)
    {
        $this->productRepository = $productRepository;
    }

    /**
     * get all the product
     * @return array the list of all product
     */
    public function getAll(): array
    {
        return $this->productRepository->findBy(['archived' => false]);
    }

    /**
     * get all the "deleted" product
     * @return array the list of all "deleted" product
     */
    public function getArchived(): array
    {
        return $this->productRepository->findBy(['archived' => true]);
    }

    /**
     * create a product
     * @param array $raw an array with all the field
     * @return Product the product created
     * @throws Exception creation failed
     */
    public function create(array $raw): Product
    {
        $product = new Product();
        $product->setName(Util::tryGet($raw, 'name'));
        $product->setCodeProduct(Util::tryGet($raw, 'code_product'));
        $product->setPrice(Util::tryGet($raw, 'price'));
        $product->setPlateforme(Util::tryGet($raw, 'plateforme'));
        $product->setImage(Util::tryGet($raw, 'image'));
        $product->setArvhived(Util::tryGet($raw, 'archived', false));

        $this->productRepository->save($product, true);
        return $product;
    }

    /**
     * get a product with his id
     * @param string $id the id of the product to get
     * @return Product the product with that id
     * @throws Exception no product found
     */
    public function read(string $id): Product
    {
        $product = $this->productRepository->find($id);
        if($product == null) throw new Exception("no product found with that id");
        return $product;
    }

    /**
     * Update the product
     * @param string $id the id of the product to update
     * @param array $raw an array with all the field to update
     * @return Product the product updated
     * @throws Exception update failed, or no product found
     */
    public function update(string $id, array $raw): Product
    {
        $product = $this->productRepository->find($id);
        if($product != null) {
            $product->setName(Util::tryGet($raw, 'name', $product->getName()));
            $product->setCodeProduct(Util::tryGet($raw, 'code_product', $product->getCodeProduct()));
            $product->setPrice(Util::tryGet($raw, 'price', $product->getPrice()));
            $product->setPlateforme(Util::tryGet($raw, 'plateforme', $product->getPlateforme()));
            $product->setImage(Util::tryGet($raw, 'image', $product->getImage()));

            if($product->isArvhived()) {
                $product->setArvhived(Util::tryGet($raw, 'archived', $product->isArvhived()));
            }

            $this->productRepository->save($product, true);
            return $product;
        } else throw new Exception("no product found with that id");
    }

    /**
     * delete the product
     * @param string $id the id of the product to delete
     * @return Product the product deleted
     * @throws Exception delete failed, or no product found
     */
    public function delete(string $id): Product
    {
        $product = $this->productRepository->find($id);
        if($product != null) {
            $product->setArvhived(true);

            $this->productRepository->save($product, true);
            return $product;
        } else throw new Exception("no product found with that id");
    }
}
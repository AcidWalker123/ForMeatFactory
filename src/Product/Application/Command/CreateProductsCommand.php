<?php

namespace App\Product\Application\Command;

use App\Product\Application\Service\ProductService;
use App\Product\Application\DTO\ProductDto;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:create-products',
    description: 'Create multiple products for testing or seeding the database',
)]
class CreateProductsCommand extends Command
{
    public function __construct(private ProductService $productService)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $productsData = [
            [
                'title' => 'Beef Ribeye Steak',
                'description' => 'Premium grass-fed ribeye steak, perfect for grilling',
                'price' => 24.99,
                'category' => 'Meat',
                'inStock' => true
            ],
            [
                'title' => 'Chicken Breast Fillet',
                'description' => 'Boneless and skinless chicken breast fillets, fresh and tender',
                'price' => 9.99,
                'category' => 'Meat',
                'inStock' => true
            ],
            [
                'title' => 'Pork Belly',
                'description' => 'Fresh pork belly with perfect fat-to-meat ratio for roasting',
                'price' => 14.50,
                'category' => 'Meat',
                'inStock' => true
            ],
            [
                'title' => 'Lamb Chops',
                'description' => 'Tender lamb chops sourced from local farms',
                'price' => 29.99,
                'category' => 'Meat',
                'inStock' => false
            ],
        ];

        $dtos = [];
        foreach ($productsData as $data) {
            $dtos[] = new ProductDto(
                id: null,
                title: $data['title'],
                description: $data['description'],
                price: $data['price'],
                category: $data['category'],
                inStock: $data['inStock']
            );
        }

        $response = $this->productService->createMultiple($dtos);

        if (!$response->isSuccess()) {
            $io->error($response->getMessage());
            return Command::FAILURE;
        }

        $io->success('Products created successfully! IDs: ' . implode(', ', $response->getData()));
        return Command::SUCCESS;
    }
}

<?php

namespace Faker\Provider;
class Commerce extends Base
{
    protected static $department = [
        "Sales", "Marketing", "Human Resources", "Finance", "Operations", "Customer Service",
        "Research and Development", "Information Technology", "Product Management", "Supply Chain Management",
        "Quality Assurance", "Legal", "Public Relations", "Business Development", "Accounting",
        "Administration", "Purchasing", "Training and Development", "Corporate Communications",
        "Strategic Planning", "Facilities Management", "Risk Management", "Internal Audit", "Project Management",
        "Creative Services", "E-commerce", "Vendor Management", "Logistics", "Regulatory Compliance",
        "Sustainability", "Market Research", "Brand Management", "Procurement", "Community Relations",
        "Employee Relations", "Data Analytics", "Event Planning", "Health and Safety", "Investor Relations",
        "Merchandising", "Social Media", "Talent Acquisition", "Training and Development",
        "Warehouse Management", "Strategic Partnerships", "Corporate Development"
    ];

    protected static $category = [
        "Electronics", "Apparel", "Home & Kitchen", "Beauty & Personal Care", "Books", "Toys & Games",
        "Health & Wellness", "Sports & Outdoors", "Automotive", "Grocery & Gourmet Food", "Pet Supplies",
        "Office Products", "Tools & Home Improvement", "Baby Products", "Jewelry", "Electrical & Lighting",
        "Furniture", "Arts & Crafts", "Industrial & Scientific", "Musical Instruments", "Software",
        "Mobile Accessories", "Outdoor Living", "Luggage & Travel Gear", "Camera & Photo", "Party Supplies",
        "Food & Beverage", "Garden & Outdoor", "Electrical Equipment", "Medical Supplies", "Home Improvement",
        "Computer Accessories", "School & Office Supplies", "Building Materials", "Kitchen & Dining",
        "Electrical Components", "Automotive Parts", "Cleaning Supplies", "Craft Supplies", "Patio & Garden",
        "Personal Care", "Travel Accessories", "Home Decor", "Storage & Organization", "Home Appliances",
        "Electrical Appliances", "Construction Materials", "Building Supplies", "Art Supplies"
    ];

    protected static $productName = [
        'adjective' => ['Small', 'Rustic', 'Ergonomic', 'Modern', 'Vintage', 'Sleek', 'Compact', 'Stylish', 'Luxurious',
            'Efficient', 'Durable', 'Versatile', 'Innovative', 'Premium', 'Customizable', 'Handcrafted',
            'Chic', 'Minimalist', 'Practical', 'Elegant', 'Contemporary', 'Sophisticated', 'Robust', 'Trendy',
            'Futuristic', 'Affordable', 'Unique', 'Functional', 'Fashionable', 'Timeless', 'High-quality', 'Exclusive',
            'Glamorous', 'Eclectic', 'Dynamic', 'Creative', 'Adaptable', 'Refined', 'Sustainable', 'Artisan',
            'Compact', 'Vibrant', 'Cosy', 'Gorgeous', 'Whimsical', 'Charming', 'Fresh', 'Cozy', 'Soothing'
        ],
        'material' => ['Wood', 'Paper', 'Metal', 'Glass', 'Leather', 'Plastic', 'Fabric', 'Ceramic', 'Stone', 'Bamboo',
            'Acrylic', 'Canvas', 'Rubber', 'Stainless Steel', 'Aluminum', 'Carbon Fiber', 'Wicker', 'Velvet', 'Suede',
            'Granite', 'Marble', 'Brass', 'Bronze', 'Copper', 'Porcelain', 'Silicone', 'Linen', 'Velour', 'Mahogany',
            'Pine', 'Oak', 'Maple', 'Teak', 'Birch', 'Cherry Wood', 'Walnut', 'Cotton', 'Silk', 'Wool', 'Polyester',
            'Fur', 'Fiberglass', 'Vinyl', 'Laminate', 'MDF (Medium-Density Fiberboard)', 'HDPE (High-Density Polyethylene)', 'PVC (Polyvinyl Chloride)'
        ],
        'product' => ['Chair', 'Car', 'Pants', 'Plate', 'Table', 'Phone', 'Watch', 'Shoes', 'Bag', 'Lamp', 'Camera', 'Book',
            'Speaker', 'Knife', 'Sofa', 'Hat', 'Bike', 'Glasses', 'Headphones', 'Guitar', 'Ring', 'Wallet', 'Scarf',
            'Monitor', 'Printer', 'Painting', 'Earrings', 'Backpack', 'Desk', 'Keyboard', 'Vase', 'Mug', 'Mirror',
            'Jacket', 'Sunglasses', 'Drone', 'Fan', 'Rug', 'Clock', 'Sculpture', 'Blender', 'Chair', 'Bench', 'Couch', 'Chandelier',
            'Necklace', 'Oven', 'Watch', 'Pot', 'Easel', 'Bottle', 'Calendar'
        ]

    ];

    protected static $sku; //represents an SKU

    protected static $upc;  //represents a universal product code

    protected static $ean;  // Represents a European Article Number

    public function sku()
    {
        return self::regexify('[A-Z][0-9]{8}');
    }

    public function upc()
    {
        return self::regexify('[0-9]{12}');
    }

    public function ean()
    {
        return self::regexify('[0-9]{13}');
    }

    public function category(): string
    {
        return static::randomElement(static::$category);
    }

    public function department(): string
    {
        return static::randomElement(static::$department);
    }

    public function productName(): string
    {
        return static::randomElement(static::$productName['adjective'])
            . ' ' . static::randomElement(static::$productName['material'])
            . ' ' . static::randomElement(static::$productName['product']);
    }
}

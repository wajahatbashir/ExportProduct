# WB_ExportProduct

WB_ExportProduct is a Magento 2 module that provides functionality for exporting product data from the catalog. The module supports exporting data to CSV and Excel formats, including custom product attributes and the base image URL.

## Features

- Export products data to CSV.
- Export products data to Excel.
- Export complete products data including attributes.
- Export products grid data based on store-wise and website-wise selection.
- Exported data files are saved in the `var/wbexport-product/` folder.

## Compatibility

This module is compatible with Magento 2.3 and Magento 2.4.

## Installation

1. **Clone the repository into your Magento 2 `app/code` directory:**

    ```bash
    git clone https://github.com/<your-username>/Magento2-ExportProduct.git app/code/WB/ExportProduct
    ```

2. **Enable the module:**

    ```bash
    php bin/magento module:enable WB_ExportProduct
    ```

3. **Run the setup upgrade and deploy static content:**

    ```bash
    php bin/magento setup:upgrade
    php bin/magento setup:static-content:deploy -f
    php bin/magento cache:clean
    ```

## Usage

1. **Navigate to the Catalog > Products section in the Magento admin panel.**
2. **You will find two new buttons at the top of the product grid: "Export to CSV" and "Export to Excel".**
3. **Click on either button to export the product data in the desired format.**

## Files and Directories

- `Controller/Adminhtml/Export/Csv.php`: Handles the export to CSV functionality.
- `Controller/Adminhtml/Export/Excel.php`: Handles the export to Excel functionality.
- `etc/adminhtml/menu.xml`: Adds the module to the admin menu.
- `etc/adminhtml/routes.xml`: Defines the admin routes for the module.
- `etc/acl.xml`: Defines the access control list (ACL) for the module.
- `etc/module.xml`: Declares the module.
- `view/adminhtml/layout/catalog_product_index.xml`: Adds the export buttons to the product grid toolbar.
- `view/adminhtml/layout/exportproduct_index_index.xml`: Defines the layout for the export product page.
- `view/adminhtml/ui_component/product_listing.xml`: Configures the product grid columns and export buttons.
- `registration.php`: Registers the module with Magento.
- `composer.json`: Module dependencies and autoload information.

## Support

For any issues or feature requests, please open an issue on the [GitHub repository](https://github.com/<your-username>/Magento2-ExportProduct).

## License

This module is proprietary and not open-source.

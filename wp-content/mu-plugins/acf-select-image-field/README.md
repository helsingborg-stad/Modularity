# ACF Image Select MU Plugin

The ACF Image Select MU (Must Use) plugin is a custom field type for Advanced Custom Fields (ACF) that allows you to use radio buttons with images as options. This makes it easy to create visually appealing and user-friendly selections in your WordPress projects.

## Installation

1. Download the latest release.
2. Upload the `acf-image-select` folder to your WordPress `mu-plugins` directory (usually `wp-content/mu-plugins`). If the `mu-plugins` directory does not exist, you can create it.
3. The plugin will be automatically activated as an MU plugin when placed in the `mu-plugins` directory.

## Usage

Once the ACF Image Select MU plugin is activated, you can use the new field type in your ACF field groups. Here's how to set it up:

1. Create or edit a field group in the ACF settings.
2. Add a new field and select "Image Select" as the field type.
3. Configure the field settings with labels (visible in the backend of a post) and the value (the name of the image and also the actual value of the field in backend).
4. Add the field group to your posts, pages, or custom post types.

## Customization

To add your own images:

1. Upload your own svg file to the assets folder. The name of the file will now be available in the field settings. It will also automatically become the value of the field.

## Limitations
**Normal Conditionals (Basic)**
The ACF Image Select plugin has the following limitations regarding conditional logic:

- It supports either "and" or "or" conditionals. Not both.
  
  **Supported Conditionals:**
  - "image select" != "condition1" AND "image select" != "condition2"
  - "image select" == "condition1" OR "image select" == "condition2"
  
  **Unsupported Conditionals:**
  - "image select" != "condition1" OR "image select" != "condition2" AND "image select" != "condition3"
 
This solution will allow basic conditional logic that will work in most cases.

**Use Acf Conditionals (Advanced)**
  - When creating an Image select field. Also create a "hidden" field with the same name like "{my_field_name}_conditional.
  - Set the conditions to this field instead of Image Select.
  - It will update the value of this field each time the Image Select field changes and work like default ACF conditions.

Please keep these limitations in mind when implementing conditional logic with the ACF Image Select field.


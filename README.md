# TestWork657 WordPress Theme

## Features
This WordPress theme implements the following functionalities:

1. **Custom Post Type - Cities**:
   - A custom post type called "Cities" has been created.
   - Each city post includes custom meta fields for **Latitude** and **Longitude**, which can be used to store geographical coordinates.
   
2. **Custom Taxonomy - Countries**:
   - A custom taxonomy titled **Countries** has been created and attached to the "Cities" custom post type. Cities can be categorized under different countries.

3. **Widget - City Temperature**:
   - A custom widget displays the **city name** and the **current temperature** by fetching data from the [OpenWeatherMap API](https://openweathermap.org/). The widget can be added to any widgetized area in the theme.

4. **Custom Template - Countries and Cities Table**:
   - A custom template displays a table listing **Countries**, **Cities**, and **Temperatures**.
   - The table is populated using a WordPress database query, and it includes a **search field** for filtering cities via **WP Ajax**.

5. **Custom Action Hooks**:
   - Custom action hooks have been placed before and after the countries-cities table in the custom template, allowing easy customization or additional functionality.

## Installation Instructions

1. **Download the Theme**:
   - Download the theme files from the GitHub repository (or download as a `.zip` file).

2. **Install the Theme in WordPress**:
   - Log in to the WordPress admin dashboard.
   - Go to **Appearance > Themes** and click **Add New**.
   - Choose **Upload Theme** and select the `.zip` file of this theme.
   - Click **Install Now** and then **Activate** the theme.

3. **Activate the Child Theme**:
   - After installation, make sure the **child theme** is activated.
   - This theme works with the **Storefront** theme as the parent theme, so ensure Storefront is installed and activated in your WordPress site.

## Usage Instructions

### Cities Custom Post Type:
1. In the WordPress admin dashboard, you will see a new menu item called **Cities**.
2. Add a new city by clicking **Cities > Add New**.
3. You will see custom fields for **Latitude** and **Longitude** in the post editing page, where you can enter the city’s geographical coordinates.
4. You can assign each city to a country using the **Countries** taxonomy, which will be displayed on the right-hand side of the editing screen.

### Countries Taxonomy:
1. The **Countries** taxonomy is connected to the **Cities** custom post type.
2. To add a new country, go to **Cities > Countries** and add new countries.
3. When creating or editing a city post, you can assign it to a country using the **Countries** taxonomy.

### City Temperature Widget:
1. Go to **Appearance > Widgets** in the WordPress admin.
2. Find the **City Temperature Widget** in the available widget list.
3. Add the widget to any widgetized area (sidebar, footer, etc.).
4. The widget will display the city name and the current temperature for the selected city, fetched from the OpenWeatherMap API.

### Custom Template for Countries and Cities Table:
1. To view the countries and cities table, navigate to the custom template page (e.g., `/countries-cities`).
2. The table will list all the countries, cities, and their respective temperatures.
3. Use the search field above the table to filter the cities in real-time via **WP Ajax**.

## Testing the Theme
1. **Cities and Countries**: Test adding and displaying city posts with latitude, longitude, and country association.
2. **Widget**: Verify the widget displays the city name and temperature using the OpenWeatherMap API.
3. **Search Functionality**: Test the search functionality in the table to filter cities based on the search term.
4. **Custom Template**: Ensure the custom template correctly displays the table with countries, cities, and temperatures.

## Requirements
- **WordPress** version 5.0 or higher.
- **Storefront** theme installed and activated.

## Custom Action Hooks
- The theme includes custom action hooks before and after the countries-cities table. You can hook additional functionality or custom code into these areas if needed.

---

Thank you for reviewing my theme! If you encounter any issues or need further assistance, feel free to reach out.

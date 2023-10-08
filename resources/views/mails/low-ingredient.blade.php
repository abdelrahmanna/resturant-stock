<!DOCTYPE html>
<html>

<head>
    <title>Inventory running low!</title>
</head>

<body>
    <p>
        <h1>Low Ingredient Alert</h1>
        <br>
        Dear Inventory Manager,
        <br>

        This is to inform you that the ingredient {{ $ingredient->name }} is running below the reorder level percentage
        of {{ $ingredient->reorder_level_percentage }}%. The current stock level is {{ $ingredient->stock_level }}.

        <br>
        Please take necessary action to reorder the ingredient as soon as possible to avoid any disruption in our
        operations. If you have any questions or concerns, please feel free to contact us.

        <br>
        Thank you for your attention to this matter.
        <br>
        Best regards,
        <br>
        {{ config('app.name') }} Team
    </p>
</body>

</html>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta name="author" content="Given Ncube">
    <meta name="keywords" content="renting,house,accomodation,zimbabwe,rented accomodation,rented house,rent,harare">
    <meta name="description" content="The fastes way to find rented accomodation in Zimbabwe">
    <meta name="application-name" content="sKotch">

    <title>{= title}</title>

    { style_tag 'application' }
    { js_include_tag 'application' }

  </head>
  <body>
      {if flush.alert_any}
        {each flush.alert as notic}
            {= notic}
        {end}
      {end}
    { yield }
  </body>
</html>

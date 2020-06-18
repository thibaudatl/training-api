# Assets Manager demo 4.0

How to demo the asset manager 4.0 new features
We will demo the naming convention and product link rule features, as well as the transformation feature.

# Pre-reqs
## Asset family
Create an asset family with you name in the code
Create the following attributes on the asset family:
  - "media" attribute of type "media file" (This attribute is automatically created, no need to manually create it) 
      -> choose this attribute to be the "main media" attribute in the "Properties" tab
  - "sku_id" of type text, don't change any configuration.
  - "thumbnail" of type "media file"

## Product Attribute
Go to settings -> attributes
Create an Asset collection attribute called "packshot_YOURNAME", and assign it to the asset family you chose earlier

Assign the "packshot_YOURNAME" attribute to the asset family you created (when scrolling down on the attribute creation page, you will see a dropdown)

Open the product family page(Setting -> families) and add you newly created attribute "PACKSHOT_YOURNAME" to the "webcams" family. 

## Verify you have the image 
Rename an image to the Product SKU of your choice that belongs to  and leave the extension of your image as is.

The Product SKU of your choice shoudl exist on the environment and if everything is setup correctly, your uploaded asset should be linked to that sku.

# Setting up the Product Link rule

## Naming convention
### Intro
This feature extract data from the filename we upload and populates the attribute "sku_id" with our filename. 
`(?P<sku_id>.*)\\.`     save any character before the "." to a variable "sku_id"
`(?:jpg|png|gif|jpeg)`  Only accept the following extensions: jpg, png, gif, jpeg

### naming convention JSON
Go to "EDIT" on your asset family & click the "Product Link Rule" tab

Copy/paste the following JSON in the naming convention area 
```
{
  "source": {
    "property": "media",
    "locale": null,
    "channel": null
  },
  "pattern": "/(?P<sku_id>.*)\\.(?:jpg|png|gif|jpeg)/",
  "abort_asset_creation_on_error": true
}
```

## Product link rule
### Intro
2 steps happens here:
  - product_selections: filter on all products where the sku = sku_id provided in the asset attribute
  - "add" the asset to the "packshot" asset collection attribute

```
[
  {
    "product_selections": [
      {
        "field": "sku",
        "value": "{{sku_id}}",
        "locale": null,
        "channel": null,
        "operator": "="
      }
    ],
    "assign_assets_to": [
      {
        "attribute": "packshot",
        "locale": null,
        "channel": null,
        "mode": "add"
      }
    ]
  }
]
```

# Testing the product link rule



## transformations
```
[
  {
    "label": "Thumbnail plus black and white transformation",
    "source": {
      "attribute": "media",
      "channel": null,
      "locale": null
    },
    "target": {
      "attribute": "thumbnail",
      "channel": null,
      "locale": null
    },
    "operations": [
      {
        "type": "thumbnail",
        "parameters": {
          "width": 150,
          "height": 150
        }
      },
      {
        "type": "colorspace",
        "parameters": {
          "colorspace": "grey"
        }
      }
    ],
    "filename_suffix": "_thumbnailBW"
  }
]
```

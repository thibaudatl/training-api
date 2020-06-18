# Assets Manager 4.0

Let's lean about the new features of the Asset Manager 

Here is a step by step process to create a product link rule, that will automatically link your uploaded assets to the products. This process has constraints, but used correctly, assigning products to assets can completely disapear from your workflow.

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
Create an Asset collection attribute called "PACKSHOT_YOURNAME", and assign it to the asset family you chose earlier

Assign the "packshot_YOURNAME" attribute to the asset family you created (when scrolling down on the attribute creation page, you will see a dropdown)

Open the product family page (Setting -> families) and add you newly created attribute "PACKSHOT_YOURNAME" to the "webcams" family. 

## Verify you have an image to upload to the asset entity 
In the folder images from your training-api github repo, you should find an image called "TODD-JSON-TEST.png"

You are welcome to rename this image to another name. This name should be a SKU of a product belonging to the webcams family

The Product SKU of your choice shoudl exist on the environment and if everything is setup correctly, your uploaded asset should be linked to that sku.

# Setting up the Product Link rule

## Naming convention
### Intro
This feature extract data from the filename we upload and populates the attribute "sku_id" with our filename. 

The "source" of the extraction : the asset attribute we inspect to extract data 
```
"source": {
    "property": "media",      # this "property" parameter can either be a media file attribute or the "code" of the asset
    "locale": null,
    "channel": null
  },
  "pattern": "/(?P<sku_id>.*)\\.(?:jpg|png|gif|jpeg)/",       # pattern of extraction, using regular expression
  "abort_asset_creation_on_error": true                       # do we abort the asset upload when the regex is not respected
```

The regex:
`(?P<sku_id>.*)\\.`     save any character before the "." to a variable "sku_id"

`(?:jpg|png|gif|jpeg)`  Only accept the following extensions: jpg, png, gif, jpeg


Now, go to "EDIT" on your asset family & click the "Product Link Rule" tab

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

CONTENT TO MODIFY: "attribute" parameter -> replace "packshot" with the actual code of the attribute you created earlier "packshot_YOURNAME"
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
        "attribute": "packshot_YOURNAME", 
        "locale": null,
        "channel": null,
        "mode": "add"
      }
    ]
  }
]
```


## transformations
Copy those lines in the "transformation" section.
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


# Testing the product link rule
Upload the image called "TODD-JSON-TEST.png" present in your "image" folder at the root of the working directory. You will have to MODIFY the code of this asset when uploading it.


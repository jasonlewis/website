title: Serving Workflow
---
## Serving Workflow

Basset serves collections one of two ways. Being aware of how Basset builds and serves assets is important.

1. **Serving Production Collections**    
Basset will first check the manifest for built collections and if the application is running within the defined production environment. If both conditions are met then Basset will serve the static collection.
2. **Serving Development Collections**    
Before every page load Basset will determine if there are any collections that have outstanding builds. A rebuild is determined when the collection meets one of the following conditions:

    a. The collection has never been built before.    
    b. One of the assets in the collection has been modified, deleted, or is completely new.    
    c. The definition of the collection has been changed.
    
    Once a collection has been rebuilt for development Basset will then continue to serve the individually built assets.
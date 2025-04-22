import "./bootstrap";
import Sortable from "sortablejs";

// DO NOT import Alpine or call Alpine.start() here if Livewire manages it.

document.addEventListener("alpine:init", () => {
    // Define your custom Alpine directive 'sortable'
    Alpine.directive("sortable", (el, { expression }, { evaluate, Alpine }) => {
        // Evaluate the expression passed to x-sortable (e.g., 'updateSectionOrder')
        let livewireMethod = evaluate(expression);

        let options = {
            // Use item attribute as the draggable selector
            draggable: "[wire\\:sortable\\.item]",
            // Use handle attribute if present
            handle: el.querySelector("[wire\\:sortable\\.handle]")
                ? "[wire\\:sortable\\.handle]"
                : undefined,
            // Optional: Define group for inter-list sorting
            group: el.getAttribute("wire:sortable-group-name")
                ? el.getAttribute("wire:sortable-group-name")
                : undefined,
            animation: 150, // Optional: animation speed
            onSort: (evt) => {
                // Get ordered IDs from the item attribute
                let items = Array.from(evt.to.children).map((item) =>
                    item.getAttribute("wire:sortable.item")
                );

                // Find the closest Livewire component instance
                let component = Alpine.findClosest(el, (cmp) => cmp.__livewire);

                if (!component) {
                    console.error(
                        "Could not find Livewire component for sortable element."
                    );
                    return;
                }

                // Check if this belongs to a group (e.g., lessons within a section)
                let groupAttr = el.getAttribute("wire:sortable-group-key");
                if (groupAttr) {
                    // Call Livewire method with items array AND the group key (sectionId)
                    component.__livewire.call(livewireMethod, items, groupAttr);
                } else {
                    // Call Livewire method with just the items array
                    component.__livewire.call(livewireMethod, items);
                }
            },
        };

        Sortable.create(el, options);
    });
});

// Livewire will typically start Alpine automatically.
// If Alpine isn't starting, you might need to manually add Alpine.start()
// AFTER the 'alpine:init' listener, but try without it first.
// import Alpine from 'alpinejs'; // You might still need the import if starting manually
// window.Alpine = Alpine;
// Alpine.start();

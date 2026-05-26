jQuery(document).ready(function($) {
    let frame;

    $('#pe-add-gallery-images').on('click', function(e) {
        e.preventDefault();

        if (frame) {
            frame.open();
            return;
        }

        frame = wp.media({
            title: 'Select or Upload Images',
            button: {
                text: 'Use these images'
            },
            multiple: true
        });

        frame.on('select', function() {
            const selection = frame.state().get('selection');
            const galleryList = $('#pe-gallery-preview');
            let ids = [];

            selection.map(function(attachment) {
                attachment = attachment.toJSON();
                ids.push(attachment.id);
                galleryList.append(`
                    <li class="pe-gallery-item" data-id="${attachment.id}">
                        <img src="${attachment.sizes.thumbnail.url}" />
                        <span class="pe-remove-image">×</span>
                    </li>
                `);
            });

            updateInput();
        });

        frame.open();
    });

    // Remove image
    $(document).on('click', '.pe-remove-image', function() {
        $(this).closest('.pe-gallery-item').remove();
        updateInput();
    });

    // Update hidden input
    function updateInput() {
        const ids = [];
        $('.pe-gallery-item').each(function() {
            ids.push($(this).data('id'));
        });
        $('#pe_portfolio_gallery_input').val(ids.join(','));
    }

    // Sortable
    $('#pe-gallery-preview').sortable({
        update: updateInput
    });
});

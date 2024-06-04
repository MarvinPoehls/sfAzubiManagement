import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ["fileInput", "previewImage", "clearImageButton"];

    connect() {
        document.querySelectorAll('button.addSkill')
            .forEach(btn => {
                btn.addEventListener("click", this.addInputToCollection.bind(this))
            });

        document.querySelectorAll('ul.newSkills li div div.col-auto')
            .forEach((skill) => {
                this.addRemoveSkillButton(skill);
            });

        document.querySelectorAll('ul.preSkills li div div.col-auto')
            .forEach((skill) => {
                this.addRemoveSkillButton(skill);
            });

        this.activeImage = this.data.get("activeImage");
        this.defaultImage = this.data.get("defaultImage");

        this.fileInputTarget.addEventListener('change', this.changePreviewImage.bind(this));
        this.clearImageButtonTarget.addEventListener('click', this.clearImage.bind(this));
    }

    addRemoveSkillButton(item) {
        const removeSkillButton = document.createElement('button');
        removeSkillButton.innerHTML = '<i class="bi bi-x-lg"></i>';
        removeSkillButton.className = 'btn btn-danger';

        item.append(removeSkillButton);

        removeSkillButton.addEventListener('click', (e) => {
            e.preventDefault();
            $(item).closest('li').remove();
        });
    }

    addInputToCollection(e) {
        const collectionHolder = document.querySelector('.' + e.currentTarget.dataset.collectionHolderClass);

        const item = document.createElement('li');

        const row = document.createElement('div');
        row.className = 'row';
        item.append(row);

        const inputCol = document.createElement('div');
        inputCol.className = 'col';
        inputCol.innerHTML = collectionHolder
            .dataset
            .prototype
            .replace(
                /__name__/g,
                collectionHolder.dataset.index
            );
        row.append(inputCol);

        let buttonCol = document.createElement('div');
        buttonCol.className = 'col-auto';
        this.addRemoveSkillButton(buttonCol);
        row.append(buttonCol);

        collectionHolder.appendChild(item);

        collectionHolder.dataset.index++;
    }

    clearImage() {
        document.getElementById('preview_image').src = ROOT + 'images/uploads/defaultProfilePicture.png';
        document.getElementById('setDefaultImage').value = 1;
        document.getElementById('add_employee_image').value = '';
        document.getElementById('clearImageButton').classList.add('d-none');
    }

    changePreviewImage(event) {
        const selectedFile = event.target.files[0];
        if (selectedFile) {
            document.getElementById('setDefaultImage').value = 0;
            document.getElementById('clearImageButton').classList.remove('d-none');

            const reader = new FileReader();

            reader.onload = (event) => {
                this.previewImageTarget.src = event.target.result;
                this.clearImageButtonTarget.classList.remove('d-none');
            };

            reader.onerror = (event) => {
                console.error('File reading failed', event.target.error);
            };

            reader.readAsDataURL(selectedFile);
        } else {
            this.clearPreview();
            this.clearImageButtonTarget.classList.add('d-none');
        }
    }

    clearPreview() {
        this.previewImageTarget.src = this.defaultImage;
    }
}
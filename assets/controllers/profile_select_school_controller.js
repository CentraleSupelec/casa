import { Controller } from '@hotwired/stimulus';

export default class extends Controller {
    static targets = ['schoolList'];
    static values = {
        url: String,
        parentId: String,
        schoolId: String,
    };

    connect() {
        this.loadchoolsForParent(this.parentIdValue, this.schoolIdValue);
    }

    changeParentSchool(event) {
        this.loadchoolsForParent(event.currentTarget.value, this.schoolIdValue);
    }

    async loadchoolsForParent(parentId, schoolId) {
        const params = new URLSearchParams({
            parentId: parentId,
            schoolId: schoolId,
        });

        const response = await fetch(`${this.urlValue}?${params.toString()}`);
        this.schoolListTarget.innerHTML = await response.text();
    }
}

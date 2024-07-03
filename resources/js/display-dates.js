import { format, formatDistanceToNow } from "date-fns";

window.addEventListener("load", () => {
    let elements = document.getElementsByClassName("format-date");
    for (let i = 0; i < elements.length; i++) {
        const el = elements[i];
        const date = new Date(el.dataset.date);
        // TODO: remove in production
        date.setHours(date.getHours() + 2);
        const title = format(date, "HH:mm do LLLL yyyy");
        const formatted = formatDistanceToNow(date, {
            addSuffix: true,
        });
        el.textContent += formatted;
        el.setAttribute("title", title);
    }
});

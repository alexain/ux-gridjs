import { Controller } from "@hotwired/stimulus";
import { Grid } from "gridjs";
import "gridjs/dist/theme/mermaid.min.css";

export default class extends Controller {
    static values = { config: Object };
    static targets = ["container"];

    connect() {
        this.beforeCache = () => this.destroy();

        // Turbo è opzionale: se non c'è, questo evento non scatterà mai
        document.addEventListener("turbo:before-cache", this.beforeCache);

        this.render();
    }

    disconnect() {
        document.removeEventListener("turbo:before-cache", this.beforeCache);
        this.destroy();
    }

    refresh() {
        this.destroy();
        this.render();
    }

    resetSort() {
        // Pragmatico: reset completo dello stato (sort incluso)
        this.destroy();
        this.render();
    }

    render() {
        const cfg = this.configValue;

        if (!cfg || !Array.isArray(cfg.columns)) {
            console.error("Grid config non valida", cfg);
            return;
        }

        const showRowNumbers = !!cfg.options?.rowNumbers;

        const rowNumCol = {
            id: "__rownum",
            name: "#",
            sort: false,
            width: "64px",
            formatter: (cell) => String(cell ?? "")
        };

        const columnDefs = cfg.columns.map((c) => ({
            id: c.id,
            name: c.name,
            sort: !!c.sortable,
            width: c.width || undefined,
            formatter: (cell) => this.formatCell(cell, c),
        }));

        const columnsForGrid = showRowNumbers ? [rowNumCol, ...columnDefs] : columnDefs;

        this.grid = new Grid({
            columns: columnsForGrid,
            search: cfg.search,
            sort: cfg.sort,
            pagination: { enabled: true, limit: cfg.pageSize },
            server: {
                url: cfg.dataUrl,
                then: (json) => json.data.map((row, i) => {
                    const base = cfg.columns.map((c) => row[c.id]);
                    return showRowNumbers ? [i + 1, ...base] : base;
                }),
                total: (json) => json.total,
            },
            // language: this.getLanguage(cfg), // opzionale se gestisci l10n
        });

        const host = this.hasContainerTarget ? this.containerTarget : this.element;

        // Anti-race: monta sempre su un nodo nuovo
        if (this._mountNode) this._mountNode.remove();
        this._mountNode = document.createElement("div");
        host.appendChild(this._mountNode);

        this.grid.render(this._mountNode);
    }

    destroy() {
        if (this._mountNode) {
            this._mountNode.remove();
            this._mountNode = null;
        }
        this.grid = null;
    }

    formatCell(cell, col) {
        if (cell === null || cell === undefined) return "";

        switch (col.type) {
            case "date": {
                const locale = col.options?.locale || "it-IT";
                const d = new Date(cell);
                if (Number.isNaN(d.getTime())) return "";
                return new Intl.DateTimeFormat(locale, { year: "numeric", month: "2-digit", day: "2-digit" }).format(d);
            }
            case "money": {
                const locale = col.options?.locale || "it-IT";
                const currency = col.options?.currency || "EUR";
                const n = Number(cell);
                if (!Number.isFinite(n)) return "";
                return new Intl.NumberFormat(locale, { style: "currency", currency }).format(n);
            }
            case "number": {
                const n = Number(cell);
                return Number.isFinite(n) ? String(n) : "";
            }
            default:
                return String(cell);
        }
    }
}

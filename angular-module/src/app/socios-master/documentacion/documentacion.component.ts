import { Component, OnInit } from "@angular/core";
import { ActivatedRoute, Route, Router } from "@angular/router";
import { NgbModal, NgbModalConfig } from "@ng-bootstrap/ng-bootstrap";
import { DocuemntacionSindicalService } from "../../servicios/docuemntacion-sindical.service";

@Component({
  selector: "app-documentacion",
  templateUrl: "./documentacion.component.html",
  styleUrls: ["./documentacion.component.css"],
})
export class DocumentacionComponent implements OnInit {
  tipo: any;
  listado: any;
  tipo_txt:string;
  count:number =0;
  constructor(
    config: NgbModalConfig,
    private route: ActivatedRoute,
    private _doc: DocuemntacionSindicalService,
    private modalService: NgbModal
  ) {
    config.backdrop = 'static';
    config.keyboard = false;
  }

  ngOnInit() {
    this.tipo = this.route.snapshot.params.tipo;
    this.nombre_tipo();
    this.traer_documento();
  }

  traer_documento() {
    this._doc.listado(this.tipo).subscribe((res) => {
      console.log(res);
      this.listado = res;
      this.count = res.length
    });
  }

  nombre_tipo(){
    this._doc.listado_tipos().subscribe(res=>{
      this.tipo_txt = res.find(i => i.id==this.tipo).descripcion;
    })
  }

  open(content) {
    this.modalService.open(content, { size: "lg" });
  }
}

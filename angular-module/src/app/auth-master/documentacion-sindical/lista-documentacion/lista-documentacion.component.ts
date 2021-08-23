import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { DocuemntacionSindicalService } from 'src/app/servicios/docuemntacion-sindical.service';

@Component({
  selector: 'app-lista-documentacion',
  templateUrl: './lista-documentacion.component.html',
  styleUrls: ['./lista-documentacion.component.css']
})
export class ListaDocumentacionComponent implements OnInit {

  tipo:string='all';
  count_listado=0;
  listado:any;
  tipos:any;
  constructor(private _documentacion:DocuemntacionSindicalService, private modalService: NgbModal) { }

  ngOnInit() {
    this.listado_tipos();
  }

  listar(){
    this._documentacion.listado(this.tipo).subscribe(res=>{
      this.listado = res;
      this.count_listado = res.length
    })
  }

  open(content) {
    this.modalService.open(content, {size: 'lg'});
  }
  listado_tipos(){
    this._documentacion.listado_tipos().subscribe(res =>{
      this.tipos = res;
    })
  }
  suspender(doc){
    var dialog = confirm('¿Quiere suspender este documento?');

    if(dialog){
      this._documentacion.suspender(doc).subscribe(res=>{
        if(res.estado){
          alert(res.mensaje);
          this.listar();
        }else{
          alert(res.mensaje)
        }
      })
    }
  }

}

import { Component, OnInit } from '@angular/core';
import { NgbModal } from '@ng-bootstrap/ng-bootstrap';
import { DocuemntacionSindicalService } from 'src/app/servicios/docuemntacion-sindical.service';

@Component({
  selector: 'app-historial',
  templateUrl: './historial.component.html',
  styleUrls: ['./historial.component.css']
})
export class HistorialComponent implements OnInit {

  tipo:string='all';
  count_listado=0;
  listado:any;
  tipos:any;
  constructor(private _documentacion:DocuemntacionSindicalService, private modalService: NgbModal) { }

  ngOnInit() {
    this.listado_tipos();
  }

  listado_tipos(){
    this._documentacion.listado_tipos().subscribe(res =>{
      this.tipos = res;
    })
  }

  listar(){
    this._documentacion.listado(this.tipo,'N').subscribe(res=>{
      this.listado = res;
      this.count_listado = res.length
    })
  }

  open(content) {
    this.modalService.open(content, {size: 'lg'});
  }

  activar(doc){
    var dialog = confirm('¿Quiere activar este documento?');

    if(dialog){
      this._documentacion.activar(doc).subscribe(res=>{
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

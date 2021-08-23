import { Component, OnInit } from '@angular/core';
import { DocuemntacionSindicalService } from '../../servicios/docuemntacion-sindical.service';

@Component({
  selector: 'app-index',
  templateUrl: './index.component.html',
  styleUrls: ['./index.component.css']
})
export class IndexComponent implements OnInit {

  tipos:any;
  constructor(private _doc:DocuemntacionSindicalService) { }

  ngOnInit() {
    this.tipos_documentacion();
  }

  tipos_documentacion(){
    this._doc.listado_tipos().subscribe(res=>{
      this.tipos = res;
    });
  }

}

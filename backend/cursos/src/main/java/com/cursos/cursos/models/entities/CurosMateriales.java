package com.cursos.cursos.models.entities;

import jakarta.persistence.*;
import java.util.Objects;

@Entity
@Table(name = "curso_materiales")
public class CurosMateriales {

    @Id
    @GeneratedValue(strategy = GenerationType.IDENTITY)
    private Long id;

    @Column(name = "material_id", nullable = false)
    private Long materialId;

    // Constructor vacío (requerido por JPA)
    public CurosMateriales() {
    }

    // Constructor con parámetros
    public CurosMateriales(Long materialId) {
        this.materialId = materialId;
    }

    // Getters y Setters
    public Long getId() {
        return id;
    }

    public void setId(Long id) {
        this.id = id;
    }

    public Long getMaterialId() {
        return materialId;
    }

    public void setMaterialId(Long materialId) {
        this.materialId = materialId;
    }

    // Implementación de equals
    @Override
    public boolean equals(Object o) {
        if (this == o) return true;
        if (o == null || getClass() != o.getClass()) return false;
        CurosMateriales that = (CurosMateriales) o;
        return Objects.equals(id, that.id);
    }

    // Implementación de hashCode
    @Override
    public int hashCode() {
        return Objects.hash(id);
    }

    // Método toString (opcional para depuración)
    @Override
    public String toString() {
        return "CurosMateriales{" +
                "id=" + id +
                ", materialId=" + materialId +
                '}';
    }
}
